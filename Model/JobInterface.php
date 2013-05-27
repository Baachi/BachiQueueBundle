<?php

namespace Bachi\QueueBundle\Model;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
interface JobInterface extends \Serializable
{
    /**
     * Return the value from the given key, if they don't
     * exist it will return $default.
     * 
     * @param  string $key     The key
     * @param  mixed  $default The default value
     * 
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets a new parameter.
     * 
     * @param string $key   The key
     * @param mixed  $value The value
     */
    public function set($key, $value);

    /**
     * Check if exists $key.
     * 
     * @param  string  $key The key.
     * 
     * @return boolean
     */
    public function has($key);    
}
