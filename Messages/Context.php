<?php

namespace Smartbox\Integration\FrameworkBundle\Messages;

use JMS\Serializer\Annotation as JMS;
use Smartbox\CoreBundle\Type\SerializableArray;

/**
 * Class Context
 * @package Smartbox\Integration\FrameworkBundle\Messages
 */
class Context implements \ArrayAccess
{
    const TRANSACTION_ID = 'transaction_id';
    const ORIGINAL_FROM = 'from';
    const ORIGINAL_TIMESTAMP = 'timestamp';
    const USER = 'user';
    const IP = 'ip';
    const API_MODE = 'api_mode';
    const VERSION = 'version';

    /**
     * @JMS\Type("Smartbox\CoreBundle\Type\SerializableArray")
     * @JMS\Expose
     * @JMS\Groups({"logs"})
     * @var SerializableArray
     */
    protected $values;

    /**
     * @param SerializableArray|array $values
     */
    public function __construct($values = [])
    {
        if ($values instanceof SerializableArray) {
            $this->values = $values;
        } else if (is_array($values)) {
            $this->values = new SerializableArray($values);
        } else {
            throw new \InvalidArgumentException("Invalid value, expected array or SerializableArray");
        }
    }

    /**
     * Get a value from the context
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->values->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return @$this->values[$offset];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("You can not mutate the context once is created");
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("You can not mutate the context once is created");
    }

    /**
     * Convert the context to an associative array
     * @return \Smartbox\CoreBundle\Type\SerializableArray
     */
    public function toArray()
    {
        return $this->values->toArray();
    }
}
