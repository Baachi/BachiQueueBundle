<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
interface StorageInterface
{
    function count($name);

    function add($name, JobInterface $job);

    function retrieve($name, $max);
}
