<?php
namespace Serato\UserProfileSdk\Queue;

use Aws\Sdk;
use Aws\Result;
use Aws\Sqs\SqsClient;
use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Exception\InvalidMessageBodyException;
use Aws\Sqs\Exception\SqsException;
use Ramsey\Uuid\Uuid;

/**
 * AWS SQS queue implementation.
 *
 * Send `Serato\UserProfileSdk\Message\AbstractMessage` instances via an SQS
 * message queue.
 */
class Sqs extends AbstractMessageQueue
{
    const MAX_BATCH_SIZE = 10;
    const FIFO_QUEUE = true;

    /* @var SqsClient */
    private $sqsClient;

    /* @var string */
    private $sqsQueueName;

    /* @var string */
    private $sqsQueueUrl;

    /* @var array */
    private $messageBatch = [];

    /**
     * Constructs the instance
     *
     * @param SqsClient     $sqsClient      An AWS SDK SQS client instance
     * @param string        $sqsQueueName   Name of SQS queue
     */
    public function __construct(SqsClient $sqsClient, $sqsQueueName, $sqsQueueUrl = null)
    {
        $this->sqsClient = $sqsClient;
        $this->sqsQueueName = $sqsQueueName;
        if ($sqsQueueUrl !== null) {
            $this->sqsQueueUrl = $sqsQueueUrl;
        }
    }

    public function __destruct()
    {
        $this->sendMessageBatch();
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(AbstractMessage $message)
    {
        $result = $this
                    ->sqsClient
                    ->sendMessage($this->messageToSqsSendParams($message));

        return $result['MessageId'];
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessageToBatch(AbstractMessage $message)
    {
        if (count($this->messageBatch) === self::MAX_BATCH_SIZE) {
            $this->sendMessageBatch();
        }
        $this->messageBatch[] = $this->messageToSqsSendParams($message, count($this->messageBatch));
    }

    /**
     * Return an `AbstractMessage` instance from a raw queue message
     *
     * @param mixed   $body         A raw queue message
     * @param array   $classMap     A map of message types to class names (optional)
     *
     * @return bool     Indicates delivery success
     *
     * @throws InvalidMessageBodyException
     */
    public static function createMessage($sqsMessage, array $classMap = [])
    {
        if (md5($sqsMessage['Body']) !== $sqsMessage['MD5OfBody']) {
            throw new InvalidMessageBodyException(
                'Message `Body` md5 hash does not match message ' .
                '`MD5OfBody` value.'
            );
        }

        $body = json_decode($sqsMessage['Body'], true);

        if ($body === null) {
            throw new InvalidMessageBodyException(
                'Message `Body` does not contain a valid JSON encoded string.'
            );
        }

        return self::getMessageFromWrappedBody(
            (int)$sqsMessage['MessageAttributes']['UserId']['StringValue'],
            $body,
            $classMap
        );
    }

    /**
     * Convert an AbstractMessage into a param array suitable for sending
     * to an SQS queue
     *
     * @param AbstractMessage   $message            Message instance
     * @param string            $batchMessageId     An ID that is unique within a batch of
     *                                              messages (required for batch operations)
     * @return array
     */
    public function messageToSqsSendParams(AbstractMessage $message, $batchMessageId = null)
    {
        return array_merge(
            [
                'MessageAttributes' => [
                    'UserId' => [
                        'DataType'      => 'Number',
                        'StringValue'   => (string)$message->getUserId()
                    ]
                ],
                'MessageBody'               => json_encode($this->getWrappedMessageBody($message)),
                'MessageDeduplicationId'    => Uuid::uuid4()->toString()
            ],
            (self::FIFO_QUEUE ? ['MessageGroupId' => (string)$message->getUserId()] : []),
            // If message is NOT part of batch add the queue URL
            // If it IS part of batch add the message ID
            ($batchMessageId === null ?
                ['QueueUrl' => $this->getQueueUrl()] :
                ['Id' => (string)$batchMessageId]
            )
        );
    }

    /**
     * Get the SQS queue URL
     *
     * @return string
     */
    public function getQueueUrl()
    {
        if ($this->sqsQueueUrl === null) {
            try {
                $result = $this->sqsClient->getQueueUrl(['QueueName' => $this->getRealQueueName()]);
                $this->sqsQueueUrl = $result['QueueUrl'];
            } catch (SqsException $e) {
                if ($e->getAwsErrorCode() === 'AWS.SimpleQueueService.NonExistentQueue') {
                    $attributes = [
                        'VisibilityTimeout'             => 60,
                        # Create queue with long polling enabled
                        'ReceiveMessageWaitTimeSeconds' => 20
                    ];
                    if (self::FIFO_QUEUE) {
                        $attributes['FifoQueue'] = 'true';
                    }
                    $result = $this->sqsClient->createQueue([
                        'QueueName' => $this->getRealQueueName(),
                        'Attributes' => $attributes
                    ]);
                    $this->sqsQueueUrl = $result['QueueUrl'];
                } else {
                    throw $e;
                }
            }
        }
        return $this->sqsQueueUrl;
    }

    /**
     * @return Result | null
     */
    private function sendMessageBatch()
    {
        if (count($this->messageBatch) > 0) {
            $result = $this
                        ->sqsClient
                        ->sendMessageBatch([
                            'Entries'   => $this->messageBatch,
                            'QueueUrl'  => $this->getQueueUrl()
                        ]);
            $this->messageBatch = [];
            return $result;
        }
    }

    /**
     * @return string
     */
    private function getRealQueueName()
    {
        return $this->sqsQueueName. (self::FIFO_QUEUE ? '.fifo' : '');
    }
}
