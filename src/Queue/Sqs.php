<?php
namespace Serato\UserProfileSdk\Queue;

use Aws\Sdk;
use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Exception\InvalidMessageBodyException;

class Sqs extends AbstractMessageQueue
{
    /* @var Sdk */
    private $awsSdk;

    /* @var string */
    private $sqsQueueUrl;

    /**
     * Constructs the instance
     *
     * @param Sdk       $awsSdk         An AWS SDK instance
     * @param string    $sqsQueueName   URL of SQS queue
     */
    public function __construct(Sdk $awsSdk, $sqsQueueUrl)
    {
        $this->awsSdk = $awsSdk;
        $this->sqsQueueUrl = $sqsQueueUrl;
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
            'QueueUrl'      => $this->sqsQueueUrl
        ];
    }
}
