<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;
use Predis\Client;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class RedisStorage implements StorageInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function count($name)
    {
        return $this->client->llen($name);
    }

    public function add($name, JobInterface $job)
    {
        $content = serialize($job);

        $this->client->rpush($name, $content);
        return true;
    }

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
