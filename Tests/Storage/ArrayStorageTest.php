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
        $storage->add('test', $job = $this->createJob());

        $this->assertEquals(1, $storage->count('test'));
    }

    public function testRetrieve()
    {
        $storage = new ArrayStorage;

        for ($i = 0; $i < 10; $i++) {
            $storage->add('test', $this->createJob());
        }

        $this->assertCount(1, $storage->retrieve('test', 1));
        $this->assertCount(3, $storage->retrieve('test', 3));
        $this->assertEquals(6, $storage->count('test'));
    }

    public function testCount()
    {
        $storage = new ArrayStorage;

        for ($i = 0; $i < 10; $i++) {
            $storage->add('test', $this->createJob());
        }

        $this->assertEquals(10, $storage->count('test'));
    }

    public function testCountWithEmptyElements()
    {
        $storage = new ArrayStorage();

        $this->assertEquals(0, $storage->count('test'));
    }
}
