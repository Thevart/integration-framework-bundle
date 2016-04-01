<?php

namespace Smartbox\Integration\FrameworkBundle\Components\Queues;

use Smartbox\CoreBundle\Type\SerializableInterface;
use Smartbox\Integration\FrameworkBundle\Core\Messages\Context;
use Smartbox\Integration\FrameworkBundle\Core\Messages\Message;

class QueueMessage extends Message implements QueueMessageInterface
{
    const HEADER_QUEUE = 'destination';
    const HEADER_PERSISTENT = 'persistent';
    const HEADER_TTL = 'ttl';
    const HEADER_EXPIRES = 'expires';
    const HEADER_TYPE = 'type';
    const HEADER_PRIORITY = 'priority';
    const HEADER_REASON_FOR_FAILURE = 'dlqDeliveryFailureCause';

    public function __construct(SerializableInterface $body = null, $headers = array(), Context $context = null)
    {
        parent::__construct($body, $headers, $context);
        $this->setPersistent(true);
    }

    public function setQueue($queue)
    {
        $this->setHeader(self::HEADER_QUEUE, $queue);
    }

    public function setExpires($expires)
    {
        $this->setHeader(self::HEADER_EXPIRES, $expires);
    }

    public function setTTL($ttl)
    {
        $this->setHeader(self::HEADER_TTL, $ttl);
        $this->setExpires((time() + $ttl) * 1000);
    }

    public function setMessageType($type)
    {
        $this->setHeader(self::HEADER_TYPE, $type);
    }

    public function setPriority($priority)
    {
        $this->setHeader(self::HEADER_PRIORITY, $priority);
    }

    public function setPersistent($persistent)
    {
        if ($persistent) {
            $this->setHeader(self::HEADER_PERSISTENT, 'true');
        } else {
            $this->setHeader(self::HEADER_PERSISTENT, 'false');
        }
    }

    public function setReasonForFailure($reason)
    {
        $this->setHeader(self::HEADER_REASON_FOR_FAILURE, $reason);
    }

    public function getQueue()
    {
        return $this->getHeader(self::HEADER_QUEUE);
    }

    public function getExpires()
    {
        return $this->getHeader(self::HEADER_QUEUE);
    }

    public function getTTL()
    {
        return $this->getHeader(self::HEADER_TTL);
    }

    public function getVersion()
    {
        return $this->getContext()->get(Context::VERSION);
    }

    public function getMessageType()
    {
        return $this->getHeader(self::HEADER_TYPE);
    }

    public function getPriority()
    {
        return $this->getHeader(self::HEADER_PRIORITY);
    }

    public function getPersistent()
    {
        return $this->getHeader(self::HEADER_PERSISTENT);
    }

    public function getReasonForFailure()
    {
        return $this->getHeader(self::HEADER_REASON_FOR_FAILURE);
    }

    /**
     * @param $uri
     */
    public function setDestinationURI($uri)
    {
        $this->setHeader(Message::HEADER_FROM, $uri);
    }

    /**
     * @return null|string
     */
    public function getDestinationURI()
    {
        return $this->getHeader(Message::HEADER_FROM);
    }
}