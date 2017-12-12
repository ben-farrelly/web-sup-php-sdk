<?php
namespace Serato\UserProfileSdk\Queue;

use Serato\UserProfileSdk\Message\AbstractMessage;
use Serato\UserProfileSdk\Exception\InvalidMessageBodyException;

/**
 * Base class for interacting with a message queue
 */
abstract class AbstractMessageQueue
{
    const MESSAGE_BODY_CLASS_NAME_KEY = 'className';
    const MESSAGE_BODY_MESSAGE_KEY = 'message';

    /**
     * Send a message to the queue
     *
     * @param AbstractMessage   $message    Message instance
     * @return mixed     A unique message identifier
     */
    abstract public function sendMessage(AbstractMessage $message);

    /**
     * Return an AbstractMessage message from a raw queue message
     *
     * @param mixed   $body    A raw queue message
     * @return bool     Indicates delivery success
     * @throws InvalidMessageBodyException
     */
    abstract public function createMessage($body);

    /**
     * Wrap a AbstractMessage instance's body with the name of the child
     * message class into an array suitable for sending to the queue.
     *
     * @param AbstractMessage   $message    Message instance
     * @return array
     */
    protected function getWrappedMessageBody(AbstractMessage $message)
    {
        return [
            self::MESSAGE_BODY_CLASS_NAME_KEY   => get_class($message),
            self::MESSAGE_BODY_MESSAGE_KEY      => $message->getParams()
        ];
    }

    /**
     * Return an AbstractMessage message from a user ID and an array of data that
     * represents a single message body read from the queue.
     *
     * @param int       $userId         User ID
     * @param array     $messageBody    Array of message body data
     * @return mixed    An AbstractMessage instance
     */
    protected static function getMessageFromWrappedBody($userId, array $messageBody)
    {
        $messageClass = $messageBody[self::MESSAGE_BODY_CLASS_NAME_KEY];
        return $messageClass::create($userId, $messageBody[self::MESSAGE_BODY_MESSAGE_KEY]);
    }
}
