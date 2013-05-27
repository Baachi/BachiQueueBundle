<?php

namespace Bachi\QueueBundle\Consuemer;

use Bachi\QueueBundle\Model\JobInterface;

interface ConsumerInterface
{
    /**
     * Consume a job.
     * 
     * @param  JobInterface $job The job
     */
    public function process(JobInterface $job);
}