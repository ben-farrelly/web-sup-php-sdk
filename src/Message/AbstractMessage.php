<?php
namespace Serato\UserProfileSdk\Message;

use Serato\UserProfileSdk\Queue\AbstractMessageQueue;

class AbstractMessage
{
    /* @var array */
    private $params = [];

    /* @var int */
    private $userId;

    /**
     * Constructs the instance
     *
     * @param int   $userId    User ID
     * @param array $body      Array of message parameters
     */
    public function __construct($userId, array $params = [])
    {
        $this->userId = $userId;
        $this->params = $params;
    }

    /**
     * Send the message to message queue
     *
     * @return mixed  A unique message identifier
     */
    public function send(AbstractMessageQueue $queue)
    {
        return $queue->sendMessage($this);
    }

    /**
     * Creates a new message instance
     *
     * @param int   $userId    User ID
     * @param array $body      Array of message parameters
     * @return self
     */
    public static function create($userId, array $params = [])
    {
        return new self($userId, $params);
    }

    /**
     * Returns the message params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns the user ID
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
