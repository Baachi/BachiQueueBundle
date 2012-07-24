<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;
use Predis\Client;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class RedisStorage implements StorageInterface
{
    const HASH_NAME = 'queue-jobs';

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function count()
    {
        return $this->client->hlen(static::HASH_NAME);
    }

    public function add(JobInterface $job)
    {
        $content = serialize($job);
        $key = uniqid(); // TODO find a better way to generate a ID

        $this->client->hset(static::HASH_NAME, $key, $content);
        return true;
    }

    public function retrieve($max)
    {
        $ids = $this->client->hkeys(static::HASH_NAME);
        $ids = array_slice($ids, 0, $max);

        $arguments = $ids;
        array_unshift($arguments, static::HASH_NAME);

        $values = call_user_func_array(array($this->client, 'hmget'), $arguments);

        $jobs = array();

        foreach ($values as $key => $value) {
            $jobs[] = unserialize($value);
            $this->client->hdel(static::HASH_NAME, $ids[$key]);
        }

        return $jobs;
    }


}
