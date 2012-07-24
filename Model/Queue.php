<?php

namespace Bachi\QueueBundle\Model;

use Bachi\QueueBundle\Storage\StorageInterface;

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
        $this->storage = $storage;
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function add(JobInterface $job)
    {
        $this->storage->add($job);

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

        if ($count > $max = count($this->storage)) {
            throw new \RuntimeException(sprintf('$count is greater then the maximum size of %d', $max));
        }

        return $this->storage->retrieve($count);
    }
}
