<?php

namespace Bachi\QueueBundle\Exception;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class QueueNotExistException extends \Exception
{
    public function __construct($name)
    {
        $message = sprintf('The queue %s does not exist!', $name);
        parent::__construct($message);
    }

}
