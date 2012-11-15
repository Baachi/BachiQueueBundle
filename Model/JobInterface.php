<?php

namespace Bachi\QueueBundle\Model;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
interface JobInterface extends \Serializable
{
    /**
     * Process the job
     */
    function process();
}
