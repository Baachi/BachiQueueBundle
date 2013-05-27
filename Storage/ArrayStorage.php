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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jobs = array();
    }

    /**
     * {@inheritDoc}
     */
    public function add($name, JobInterface $job)
    {
        if (!isset($this->jobs[$name])) {
            $this->jobs[$name] = array();
        }

        $this->jobs[$name][] = serialize($job);
    }

    /**
     * {@inheritDoc}
     */
    public function count($name)
    {
        if (!isset($this->jobs[$name])) {
            return 0;
        }

        return count($this->jobs[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve($name, $max)
    {
        $jobs = array();

        if (!isset($this->jobs[$name])) {
            return array();
        }

        foreach (array_slice($this->jobs[$name], 0, $max) as $job) {
            $jobs[] = unserialize($job);
        }

        $this->jobs[$name] = array_slice($this->jobs[$name], $max);

        return $jobs;
    }
}
