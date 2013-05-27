<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;
use Predis\Client;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class RedisStorage implements StorageInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Constructor
     * 
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function count($name)
    {
        return $this->client->llen($name);
    }

    /**
     * {@inheritDoc}
     */
    public function add($name, JobInterface $job)
    {
        $content = serialize($job);
        $this->client->rpush($name, $content);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve($name, $max)
    {
        $jobs = array();
        while ($max > 0) {
            $string = $this->client->lpop($name);

            if ($string) {
                $jobs[] = unserialize($string);
            }
            $max--;
        }

        return $jobs;
    }
}