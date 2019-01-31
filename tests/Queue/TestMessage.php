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
        $this->setParam('scalarValue', $val);
        return $this;
    }

    public function setArrayValue(array $val)
    {
        $this->setParam('arrayValue', $val);
        return $this;
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
