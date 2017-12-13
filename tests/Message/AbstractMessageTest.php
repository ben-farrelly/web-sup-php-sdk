<?php
namespace Serato\UserProfileSdk\Test\Message;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Serato\UserProfileSdk\Message\AbstractMessage;

class AbstractMessageTest extends PHPUnitTestCase
{
    public function testGetMethods()
    {
        $userId = 111;
        $params = ['param1' => 'val1', 'param2' => 22];
        $mockMessage = $this->getMockForAbstractClass(
            AbstractMessage::class,
            [$userId, $params]
        );

        $this->assertEquals($userId, $mockMessage->getUserId());
        $this->assertEquals($params, $mockMessage->getParams());
    }
}
