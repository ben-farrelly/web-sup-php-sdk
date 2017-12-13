<?php
namespace Serato\UserProfileSdk\Test\Queue;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use Aws\Sdk;
use Aws\Result;
use Aws\MockHandler;
use Aws\Credentials\CredentialProvider;
use Serato\UserProfileSdk\Queue\Sqs;
use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Test\Queue\TestMessage;
use Ramsey\Uuid\Uuid;

class SqsTest extends PHPUnitTestCase
{
    public function testSendMessage()
    {
        $mockMessage = $this->getMockForAbstractClass(AbstractMessage::class, [111]);

        $results = [
            ['QueueUrl'  => 'my-queue-url'],
            ['MessageId' => 'TestMessageId1'],
            ['MessageId' => 'TestMessageId2']
        ];
        $queue = new Sqs($this->getMockedAwsSdk($results), 'my-queue-name');

        # Test of one syntax forms
        $this->assertEquals('TestMessageId1', $queue->sendMessage($mockMessage));
        # And the other form
        $this->assertEquals('TestMessageId2', $mockMessage->send($queue));
    }

    /**
     * @expectedException \Serato\UserProfileSdk\Exception\InvalidMessageBodyException
     */
    public function testCreateMessageWithInvalidMd5()
    {
        $queue = new Sqs($this->getMockedAwsSdk(), 'my-queue-name');
        $queue->createMessage([
            'Body'      => 'A message body',
            'MD5OfBody' => md5('A different message body')
        ]);
    }

    public function testCreateMessage()
    {
        $results = [
            ['QueueUrl'  => 'my-queue-url'],
        ];

        $queue = new Sqs($this->getMockedAwsSdk($results), 'my-queue-url');

        # We need to construct a valid `Result` array to pass into the
        # Sqs::createMessage method.
        # Easiest way to do this is to create a mock message and use
        # the Sqs::messageToSqsSendParams method.

        $userId = 666;
        $params = ['param1' => 'val1', 'param2' => 22];

        $mockMessage = $this->getMockForAbstractClass(AbstractMessage::class, [666, $params]);

        $sqsSendParams = $queue->messageToSqsSendParams($mockMessage);

        $sqsReceiveResult = [
            'Body'              => $sqsSendParams['MessageBody'],
            'MD5OfBody'         => md5($sqsSendParams['MessageBody']),
            'MessageAttributes' => $sqsSendParams['MessageAttributes']
        ];

        $receivedMockMessage = $queue->createMessage($sqsReceiveResult);

        $this->assertEquals($receivedMockMessage->getUserId(), $userId);
        $this->assertEquals($receivedMockMessage->getParams(), $params);
    }

    /**
     * @group aws-integration
     */
    public function testAwsIntegrationTest()
    {
        $userId = 555;
        $scalarMessageValue = 'A scalar value';
        $arrayMessageValue = ['param1' => 'val1', 'param2' => 22];

        $queue_name = 'SeratoUserProfile-Events-Test-' . Uuid::uuid4()->toString();

        # Credentials come from:
        # - credentials file on dev VMs
        # - .env files on build VMs

        $sdk = new Sdk([
            'region' => 'us-east-1',
            'version' => '2014-11-01',
            'credentials' => CredentialProvider::memoize(
                CredentialProvider::chain(
                    CredentialProvider::ini(),
                    CredentialProvider::env()
                )
            )
        ]);

        $supQueue = new Sqs($sdk, $queue_name);

        # Send message via `Serato\UserProfileSdk\Queue\Sqs` instance
        $messageId = TestMessage::create($userId)
                        ->setScalarValue($scalarMessageValue)
                        ->setArrayValue($arrayMessageValue)
                        ->send($supQueue);

        # Use the `Aws\Sdk` instance to receive the message
        # (receiving messages is not the responsibility of the `Serato\UserProfileSdk` SDK)
        $result = [];
        $polls = 0;
        $awsSqs = $sdk->createSqs(['version' => '2012-11-05']);
        # Might need to poll the queue a few times before getting the message
        # But limit to 5 attempts
        while ($polls < 5 && (!isset($result['Messages']) || count($result['Messages']) === 0)) {
            $result = $awsSqs->receiveMessage([
                'WaitTimeSeconds'       => 20,
                'MessageAttributeNames' => ['All'],
                'QueueUrl'              => $supQueue->getQueueUrl()
            ]);
            $polls++;
        }

        $this->assertTrue(isset($result['Messages']) && count($result['Messages']) > 0);

        if (isset($result['Messages']) && count($result['Messages']) > 0) {
            $message = $result['Messages'][0];
            $this->assertEquals($message['MessageId'], $messageId);

            $testMessageReceived = $supQueue->createMessage($message);

            $this->assertEquals($testMessageReceived->getScalarValue(), $scalarMessageValue);
            $this->assertEquals($testMessageReceived->getArrayValue(), $arrayMessageValue);
        }

        # Delete the queue using the `Aws\Sdk` instance
        $awsSqs->deleteQueue(['QueueUrl' => $supQueue->getQueueUrl()]);
    }

    /**
     * @param array $mockResults    An array of mock results to return from SDK clients
     * @return Sdk
     */
    protected function getMockedAwsSdk(array $mockResults = [])
    {
        $mock = new MockHandler();
        foreach ($mockResults as $result) {
            $mock->append(new Result($result));
        }
        return new Sdk([
            'region' => 'us-east-1',
            'version' => '2014-11-01',
            'credentials' => [
                'key' => 'my-access-key-id',
                'secret' => 'my-secret-access-key'
            ],
            'handler' => $mock
        ]);
    }
}
