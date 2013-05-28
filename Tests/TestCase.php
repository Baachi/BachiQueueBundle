<?php

namespace Bachi\QueueBundle\Tests;

use Bachi\QueueBundle\Model\Job;
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

        return new Job($data);
    }

    protected function createContainerMock()
    {
        return $this->getMock('Symfony\\Component\\DependencyInjection\\Container');
    }
}
