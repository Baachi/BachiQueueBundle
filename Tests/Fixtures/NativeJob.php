<?php

namespace Bachi\QueueBundle\Tests\Fixtures;

use Bachi\QueueBundle\Model\JobInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class NativeJob implements JobInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    /**
     * Process the job
     */
    public function process()
    {
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }
}
