<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
interface StorageInterface extends \Countable
{
    function add(JobInterface $job);

    function retrieve($max);
}
