<?php

namespace Bachi\QueueBundle\Model;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
interface QueueManagerInterface
{
    /**
     * Get a queue interface
     *
     * @param string $name
     *
     * @throws QueueNotExistException
     *
     * @return QueueInterface
     */
    function get($name);

    /**
     * Return true, if the manager contains a queue
     * that matching $name.
     *
     * @param string $name
     *
     * @return Boolean
     */
    function has($name);

    /**
     * Register a queue
     *
     * @param QueueInterface $queue The queue object
     */
    function register(QueueInterface $queue);

    /**
     * Unregister a queue from the manager.
     * Return true, if the name was found
     * and the queue was removed.
     *
     * @param string $name The queue name
     *
     * @throws QueueNotExistException
     */
    function unregister($name);
}
