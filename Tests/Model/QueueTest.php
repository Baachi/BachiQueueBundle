<?php

namespace Bachi\QueueBundle\Tests\Model;

use Bachi\QueueBundle\Model\Queue;
use Bachi\QueueBundle\Storage\ArrayStorage;
use Bachi\QueueBundle\Tests\TestCase;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class QueueTest extends TestCase
{
    public function testAdd()
    {
        $data = array();
        $job = $this->createJob();

        $storage = new ArrayStorage();

        $queue = new Queue($storage, 'test');

        $this->assertTrue($queue->add($job));

        $this->assertCount(1, $storage->getJobs());
        $this->assertEquals(serialize($job), current($storage->getJobs()));
    }

    public function testRetrieve()
    {
        $queue = new Queue(new ArrayStorage, 'test');

        for ($i = 0; $i < 10; $i++) {
            $queue->add($this->createJob());
        }

        $this->assertCount(10, $queue->retrieve(10));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRetrieveWithInvalidAmount()
    {
        $queue = new Queue(new ArrayStorage(), 'test');

        $queue->retrieve(1);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRetrieveWithZero()
    {
        $queue = new Queue(new ArrayStorage(), 'test');

        $queue->retrieve(0);
    }
}
