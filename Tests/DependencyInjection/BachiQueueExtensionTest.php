<?php

namespace Bachi\QueueBundle\Tests\DependencyInjection;

use Bachi\QueueBundle\DependencyInjection\BachiQueueExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class BachiQueueExtensionTest extends \PHPUnit_Framework_Testcase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();

        $extension = new BachiQueueExtension();
        $extension->load(Yaml::parse(file_get_contents(__DIR__.'/../Fixtures/config/minimal.yml')), $container);

        $this->assertTrue($container->hasDefinition('bachi_queue.queue_manager'));
        $queueManager = $container->get('bachi_queue.queue_manager');

        $queue = $queueManager->get('main');
        $this->assertInstanceOf('Bachi\\QueueBundle\\Model\\QueueInterface', $queue);
        $this->assertInstanceOf('Bachi\\QueueBundle\\Storage\\RedisStorage', $queue->getStorage());
    }
}