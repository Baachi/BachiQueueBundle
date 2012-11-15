<?php

namespace Bachi\QueueBundle\Tests\Model;

use Bachi\QueueBundle\Model\QueueManager;
use Bachi\QueueBundle\Model\Queue;
use Bachi\QueueBundle\Storage\ArrayStorage;
use Bachi\QueueBundle\Tests\TestCase;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class QueueManagerTest extends TestCase
{
    /**
     * @var QueueManager
     */
    private $manager;

    protected function setup()
    {
        $this->manager = new QueueManager;
    }

    public function testGet()
    {
        $this->manager->register($queue = $this->createQueue());

        $this->assertEquals($queue, $this->manager->get('test'));
    }

    /**
     * @expectedException Bachi\QueueBundle\Exception\QueueNotExistException
     */
    public function testGetANotExistQueue()
    {
        $this->manager->get('not-exist');
    }

    public function testHas()
    {
        $this->assertFalse($this->manager->has('test'));
        $this->manager->register($this->createQueue());
        $this->assertTrue($this->manager->has('test'));
    }

    public function testRegister()
    {
        $this->manager->register($this->createQueue());
    }

    public function testUnregister()
    {
        $this->manager->register( $queue = $this->createQueue());
        $this->assertTrue($this->manager->has('test'));
        $this->manager->unregister('test');
        $this->assertFalse($this->manager->has('test'));
    }

    /**
     * @expectedException Bachi\QueueBundle\Exception\QueueNotExistException
     */
    public function testUnregisterANotExistQueue()
    {
        $this->manager->unregister('not-exist');
    }
}
