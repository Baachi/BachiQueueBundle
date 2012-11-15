<?php

namespace Bachi\QueueBundle\Model;

use Bachi\QueueBundle\Storage\StorageInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
interface QueueInterface
{
    /**
     * Adds a job to the queue
     *
     * @param JobInterface $job
     *
     * @return Boolean
     */
    function add(JobInterface $job);

    /**
     * Return the storage
     *
     * @return StorageInterface
     */
    function getStorage();

    /**
     * Fetch jobs from the queue
     *
     * @param integer $count
     *
     * @return array
     */
    function retrieve($count);

    /**
     * Return the queue name
     *
     * @return string
     */
    function getName();
}
