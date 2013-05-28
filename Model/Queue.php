<?php

namespace Bachi\QueueBundle\Model;

use Bachi\QueueBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class Queue implements QueueInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var string
     */
    private $name;

    /**
     * Constructor
     *
     * @param StorageInterface $storage
     * @param $name
     */
    public function __construct(StorageInterface $storage, $name)
    {
        $this->name = $name;
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritDoc}
     */
    public function add(JobInterface $job)
    {
        $this->storage->add($this->name, $job);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve($count)
    {
        if (1 > $count) {
            throw new \RuntimeException('$count must be greater then 0');
        }

        $jobs = $this->storage->retrieve($this->name, $count);

        if (empty($jobs)) {
            return null;
        }

        return $jobs;
    }
}
