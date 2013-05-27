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
        $this->client->del('test'); 
    }


    public function testAdd()
    {
        $job = $this->createJob();

        $this->assertTrue($this->storage->add('test', $job));
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->storage->count('test'));

        $this->storage->add('test', $this->createJob());
        $this->storage->add('test', $this->createJob());

        $this->assertEquals(2, $this->storage->count('test'));
    }

    public function testCountWithEmptyElements()
    {
        $this->assertEquals(0, $this->storage->count('test'));
    }

    public function testRetrieve()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->storage->add('test', $this->createJob());
        }

        $this->assertCount(2, $this->storage->retrieve('test', 2));
        $this->assertEquals(8, $this->storage->count('test'));
    }
}
