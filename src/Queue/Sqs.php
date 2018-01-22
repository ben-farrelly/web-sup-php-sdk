<?php
namespace Serato\UserProfileSdk\Queue;

use Aws\Sdk;
use Aws\Sqs\SqsClient;
use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Exception\InvalidMessageBodyException;
use Aws\Sqs\Exception\SqsException;

class Sqs extends AbstractMessageQueue
{
    const FIFO_QUEUE        = true;
    const MESSAGE_GROUP_ID  = 'user_profile_events';

    /* @var SqsClient */
    private $sqsClient;

    /* @var string */
    private $sqsQueueName;

    /* @var string */
    private $sqsQueueUrl;

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
     * Return an `AbstractMessage` instance from a raw queue message
     *
     * @param mixed   $body    A raw queue message
     * @return bool     Indicates delivery success
     * @throws InvalidMessageBodyException
     */
    public static function createMessage($sqsMessage)
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
            $body
        );
    }

    /**
     * Convert an AbstractMessage into a param array suitable for sending
     * to an SQS queue
     *
     * @param AbstractMessage   $message    Message instance
     * @return array
     */
    public function messageToSqsSendParams(AbstractMessage $message)
    {
        return array_merge(
            [
                'MessageAttributes' => [
                    'UserId' => [
                        'DataType'      => 'Number',
                        'StringValue'   => (string)$message->getUserId()
                    ]
                ],
                'MessageBody'       => json_encode($this->getWrappedMessageBody($message)),
                'QueueUrl'          => $this->getQueueUrl()
            ],
            (self::FIFO_QUEUE ? ['MessageGroupId' => self::MESSAGE_GROUP_ID] : [])
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
                        $attributes['ContentBasedDeduplication'] = 'true';
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
     * @return string
     */
    private function getRealQueueName()
    {
        return $this->sqsQueueName. (self::FIFO_QUEUE ? '.fifo' : '');
    }
}
