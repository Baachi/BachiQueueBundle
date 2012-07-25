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
        return $this->client->hlen($name);
    }

    public function add($name, JobInterface $job)
    {
        $content = serialize($job);
        $key = uniqid(); // TODO find a better way to generate a ID

        $this->client->hset($name, $key, $content);
        return true;
    }

    public function retrieve($name, $max)
    {
        $ids = $this->client->hkeys($name);
        $ids = array_slice($ids, 0, $max);

        $arguments = $ids;
        array_unshift($arguments, $name);

        $values = call_user_func_array(array($this->client, 'hmget'), $arguments);

        $jobs = array();

        foreach ($values as $key => $value) {
            $jobs[] = unserialize($value);
            $this->client->hdel($name, $ids[$key]);
        }

        return $jobs;
    }


}
