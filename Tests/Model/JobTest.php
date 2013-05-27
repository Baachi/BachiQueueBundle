<?php

namespace Bachi\QueueBundle\Tests\Model;

use Bachi\QueueBundle\Tests\TestCase;
use Bachi\QueueBundle\Model\Job;

class JobTest extends TestCase
{
    protected function setUp()
    {
        $this->job = new Job();
    }

    public function testGet()
    {
        $this->job->set('foo', 'bar');
        $this->assertEquals('bar', $this->job->get('foo'));
    }

    public function testGetWithDefaultvalue()
    {
        $this->assertNull($this->job->get('foo'));
    }

    public function testHas()
    {
        $this->assertFalse($this->job->has('foo'));
        $this->job->set('foo', '');
        $this->assertTrue($this->job->has('foo'));
    }

    public function testSerialize()
    {
        $string = serialize($this->job);
        $job = unserialize($string);

        $this->assertEquals($job, $this->job);
    }
}