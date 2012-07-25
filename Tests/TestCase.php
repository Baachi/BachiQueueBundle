<?php

namespace Bachi\QueueBundle\Tests;

use Bachi\QueueBundle\Tests\Fixture\NativeJob;
use Bachi\QueueBundle\Model\Queue;
use Bachi\QueueBundle\Storage\ArrayStorage;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function createQueue()
    {
        return new Queue(new ArrayStorage, 'test');
    }

    protected function createJob()
    {
        $data = array(
            'hello world',
            1234.34,
            array('hello'),
        );

        return new NativeJob($data);
    }
}
