<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class ArrayStorage implements StorageInterface
{
    /**
     * @var array
     */
    private $jobs;

    public function __construct()
    {
        $this->jobs = array();
    }

    /**
     * @return array
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    public function add(JobInterface $job)
    {
        $this->jobs[] = serialize($job);
    }

    public function count()
    {
        return count($this->jobs);
    }

    public function retrieve($max)
    {
        $jobs = array();

        foreach (array_slice($this->jobs, 0, $max) as $job) {
            $jobs[] = unserialize($job);
        }

        $this->jobs = array_slice($this->jobs, $max);

        return $jobs;
    }
}
