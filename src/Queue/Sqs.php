<?php
namespace Serato\UserProfileSdk\Queue;

use Aws\Sdk;
use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Exception\InvalidMessageBodyException;
use Aws\Sqs\Exception\SqsException;

class Sqs extends AbstractMessageQueue
{
    /* @var Sdk */
    private $awsSdk;

    /* @var string */
    private $sqsQueueName;

    /* @var string */
    private $sqsQueueUrl;

    /**
     * Constructs the instance
     *
     * @param Sdk       $awsSdk         An AWS SDK instance
     * @param string    $sqsQueueName   Name of SQS queue
     */
    public function __construct(Sdk $awsSdk, $sqsQueueName, $sqsQueueUrl = null)
    {
        $this->awsSdk = $awsSdk;
        $this->sqsQueueName = $sqsQueueName;
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(AbstractMessage $message)
    {
        $result = $this
                    ->awsSdk
                    ->createSqs(['version' => '2012-11-05'])
                    ->sendMessage($this->messageToSqsSendParams($message));

        return $result['MessageId'];
    }

    /**
     * {@inheritdoc}
     */
    public function createMessage($sqsMessage)
    {
        if (md5($sqsMessage['Body']) !== $sqsMessage['MD5OfBody']) {
            throw new InvalidMessageBodyException(
                'Message `Body` md5 hash does not match message ' .
                '`MD5OfBody` value.'
            );
        }

        return $this->getMessageFromWrappedBody(
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
            $sqsClient = $this->awsSdk->createSqs(['version' => '2012-11-05']);
            try {
                $result = $sqsClient->getQueueUrl(['QueueName' => $this->sqsQueueName]);
                $this->sqsQueueUrl = $result['QueueUrl'];
            } catch (SqsException $e) {
                if ($e->getAwsErrorCode() === 'AWS.SimpleQueueService.NonExistentQueue') {
                    $result = $sqsClient->createQueue([
                        'QueueName' => $this->sqsQueueName,
                        'Attributes' => ['VisibilityTimeout' => 60]
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
