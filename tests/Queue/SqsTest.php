<?php
namespace Serato\UserProfileSdk\Test\Queue;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

use Aws\Sdk;
use Aws\Result;
use Aws\MockHandler;
use Serato\UserProfileSdk\Queue\Sqs;
use Serato\UserProfileSdk\Message\AbstractMessage;

class SqsTest extends PHPUnitTestCase
{
    public function testSendMessage()
    {
        $mockMessage = $this->getMockForAbstractClass(AbstractMessage::class, [111]);

        $results = [
            ['MessageId' => 'TestMessageId1'],
            ['MessageId' => 'TestMessageId2']
        ];
        $queue = new Sqs($this->getAwsSdk($results), 'my-queue-url');

        # Test of one syntax form
        $this->assertEquals('TestMessageId1', $queue->sendMessage($mockMessage));
        # And the other form
        $this->assertEquals('TestMessageId2', $mockMessage->send($queue));
    }

    /**
     * @expectedException \Serato\UserProfileSdk\Exception\InvalidMessageBodyException
     */
    public function testCreateMessageWithInvalidMd5()
    {
        $queue = new Sqs($this->getAwsSdk(), 'my-queue-url');
        $queue->createMessage([
            'Body'      => 'A message body',
            'MD5OfBody' => md5('A different message body')
        ]);
    }

    public function testCreateMessage()
    {
        $queue = new Sqs($this->getAwsSdk(), 'my-queue-url');

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
     * @param array $mockResults    An array of mock results to return from SDK clients
     * @return Sdk
     */
    protected function getAwsSdk(array $mockResults = [])
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
