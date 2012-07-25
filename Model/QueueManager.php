<?php

namespace Bachi\QueueBundle\Model;

use Bachi\QueueBundle\Exception\QueueNotExistException;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class QueueManager implements QueueManagerInterface
{
    private $queues;

    public function __construct()
    {
        $this->queues = array();
    }

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        if (!isset($this->queues[$name])) {
            throw new QueueNotExistException($name);
        }

        return $this->queues[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function has($name)
    {
        return isset($this->queues[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function register($name, QueueInterface $queue)
    {
        $this->queues[$name] = $queue;
    }

    /**
     * {@inheritDoc]
     */
    public function unregister($name)
    {
        if (!isset($this->queues[$name])) {
            throw new QueueNotExistException($name);
        }

        unset($this->queues[$name]);
        return true;
    }
}
