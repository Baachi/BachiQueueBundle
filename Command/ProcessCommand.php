<?php

namespace Bachi\QueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Process the queue
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class ProcessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bachi:queue:process')
            ->addArgument('queue', InputArgument::REQUIRED, 'The queue to process')
            ->addArgument('max-jobs', InputArgument::OPTIONAL, '', 5);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = $input->getArgument('queueName');
        $max = $input->getArgument('max-jobs');

        $queue = $this->getContainer()->get('bachi_queue.queue_manager')->get($queueName);

        while (null !== $jobs = $queue->retrieve($max)) {
            foreach ($jobs as $job) {
                $job->process();
            }
        }
    }


}
