<?php

namespace Bachi\QueueBundle\Model;

use Bachi\QueueBundle\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

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
     * @var Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param StorageInterface $storage
     * @param $name
     */
    public function __construct(Container $container, StorageInterface $storage, $name)
    {
        $this->storage = $storage;
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

        if ($count > $max = $this->storage->count($this->name)) {
            throw new \RuntimeException(sprintf('$count is greater then the maximum size of %d', $max));
        }

        $jobs = $this->storage->retrieve($this->name, $count);
        $container = $this->container;

        array_walk($jobs, function(JobInterface $job) use ($container) {
            if ($job instanceof ContainerAwareInterface) {
                $job->setContainer($container);
            }
        });

        return $jobs;
    }
}
