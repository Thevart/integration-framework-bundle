<?php

namespace Smartbox\Integration\FrameworkBundle\Events;

use JMS\Serializer\Annotation as JMS;
use Smartbox\Integration\FrameworkBundle\Core\Exchange;
use Smartbox\Integration\FrameworkBundle\Core\Processors\Processor;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ProcessingErrorEvent.
 */
class ProcessingErrorEvent extends ProcessEvent
{
    const EVENT_NAME = 'smartesb.event.error';

    /**
     * @var \Exception
     * @JMS\Type("Exception")
     * @JMS\Groups({"logs"})
     * @JMS\Expose
     */
    protected $exception;

    /**
     * @var RequestStack
     * @JMS\Type("Symfony\Component\HttpFoundation\RequestStack")
     * @JMS\Groups({"logs"})
     * @JMS\Expose
     */
    protected $requestStack;

    /**
     * @param Processor  $processor
     * @param Exchange   $exchange
     * @param \Exception $exception
     * @param string     $eventName
     */
    public function __construct(
        Processor $processor,
        Exchange $exchange,
        \Exception $exception,
        $eventName = self::EVENT_NAME
    ) {
        parent::__construct($eventName);
        $this->processor = $processor;
        $this->exchange = $exchange;
        $this->exception = $exception;
    }

    /**
     * @return Exchange
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return Processor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return RequestStack
     */
    public function getRequestStack()
    {
        return $this->requestStack;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
