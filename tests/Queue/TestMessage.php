<?php
namespace Serato\UserProfileSdk\Test\Queue;

use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A POC concreate implementation of AbstractMessage
 */
class TestMessage extends AbstractMessage
{
    public function setScalarValue($val)
    {
        return $this->setParam('scalarValue', $val);
    }

    public function setArrayValue(array $val)
    {
        return $this->setParam('arrayValue', $val);
    }

    public function getScalarValue()
    {
        return $this->getParam('scalarValue');
    }

    public function getArrayValue()
    {
        return $this->getParam('arrayValue');
    }
}
