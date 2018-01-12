<?php
namespace Serato\UserProfileSdk\Queue;

use Aws\Sdk;
use Aws\Sqs\SqsClient;
use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Exception\InvalidMessageBodyException;
use Aws\Sqs\Exception\SqsException;

class Sqs extends AbstractMessageQueue
{
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
    public static function createMessage($sqsMessage)
    {
        if (md5($sqsMessage['Body']) !== $sqsMessage['MD5OfBody']) {
            throw new InvalidMessageBodyException(
                'Message `Body` md5 hash does not match message ' .
                '`MD5OfBody` value.'
            );
        }

        return self::getMessageFromWrappedBody(
            (int)$sqsMessage['MessageAttributes']['UserId']['StringValue'],
            json_decode($sqsMessage['Body'], true)
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
        return [
            'MessageAttributes' => [
                'UserId' => [
                    'DataType'      => 'Number',
                    'StringValue'   => (string)$message->getUserId()
                ]
            ],
            'MessageBody'   => json_encode($this->getWrappedMessageBody($message)),
            'QueueUrl'      => $this->getQueueUrl()
        ];
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
                $result = $this->sqsClient->getQueueUrl(['QueueName' => $this->sqsQueueName]);
                $this->sqsQueueUrl = $result['QueueUrl'];
            } catch (SqsException $e) {
                if ($e->getAwsErrorCode() === 'AWS.SimpleQueueService.NonExistentQueue') {
                    $result = $this->sqsClient->createQueue([
                        'QueueName' => $this->sqsQueueName,
                        'Attributes' => [
                            'VisibilityTimeout'             => 60,
                            # Create queue with long polling enabled
                            'ReceiveMessageWaitTimeSeconds' => 20
                        ]
                    ]);
                    $this->sqsQueueUrl = $result['QueueUrl'];
                } else {
                    throw $e;
                }
            }
        }
        return $this->sqsQueueUrl;
    }
}
