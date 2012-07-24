<?php

namespace Bachi\QueueBundle\Model;

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
     * Fetch jobs from the queue
     *
     * @param integer $count
     *
     * @return array
     */
    function retrieve($count);
}
