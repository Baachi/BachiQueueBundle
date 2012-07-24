<?php

namespace Bachi\QueueBundle\Tests\Storage;

use Bachi\QueueBundle\Tests\TestCase;
use Bachi\QueueBundle\Storage\RedisStorage;
use Predis\Client;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class RedisStorageTest extends TestCase
{
    protected function setup()
    {
        if (!class_exists('Predis\\Client')) {
            self::markTestSkipped('Predis library not installed');
        }

        $this->client  = new Client();
        $this->storage = new RedisStorage($this->client);
    }

    protected function tearDown()
    {
        $ids = $this->client->hkeys(RedisStorage::HASH_NAME);
        foreach ($ids as $id) {
            $this->client->hdel(RedisStorage::HASH_NAME, $id);
        }
    }


    public function testAdd()
    {
        $job = $this->createJob();

        $this->assertTrue($this->storage->add($job));
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->storage->count());

        $this->storage->add($this->createJob());
        $this->storage->add($this->createJob());

        $this->assertCount(2, $this->storage);
    }

    public function testRetrieve()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->storage->add($this->createJob());
        }

        $this->assertCount(2, $this->storage->retrieve(2));
        $this->assertCount(8, $this->storage);
    }
}
