<?php

namespace Bachi\QueueBundle\Tests\Storage;

use Bachi\QueueBundle\Tests\TestCase;
use Bachi\QueueBundle\Storage\ArrayStorage;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class ArrayStorageTest extends TestCase
{
    public function testAdd()
    {
        $storage = new ArrayStorage;
        $storage->add($job = $this->createJob());

        $this->assertCount(1, $storage->getJobs());
        $this->assertEquals(serialize($job), current($storage->getJobs()));
    }

    public function testRetrieve()
    {
        $storage = new ArrayStorage;

        for ($i = 0; $i < 10; $i++) {
            $storage->add($this->createJob());
        }

        $this->assertCount(1, $storage->retrieve(1));
        $this->assertCount(3, $storage->retrieve(3));
    }

    public function testCount()
    {
        $storage = new ArrayStorage;

        for ($i = 0; $i < 10; $i++) {
            $storage->add($this->createJob());
        }

        $this->assertCount(10, $storage);
    }
}
