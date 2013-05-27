<?php

namespace Bachi\QueueBundle\Model;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class Job implements JobInterface
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * Construtor
     * 
     * @param array $params
     */
    public function __construct(array $params = array())
    {
        $this->parameters = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize($this->parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        $this->parameters = unserialize($serialized);
    }
}