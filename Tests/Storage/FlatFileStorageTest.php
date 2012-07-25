<?php

namespace Bachi\QueueBundle\Tests\Storage;

use Bachi\QueueBundle\Tests\TestCase;
use Bachi\QueueBundle\Storage\FlatFileStorage;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class FlatFileStorageTest extends TestCase
{
    /**
     * @var FlatFileStorage
     */
    private $storage;

    protected function setup()
    {
        $this->storage = new FlatFileStorage(sys_get_temp_dir().'/jobs-tests');
    }

    protected function tearDown()
    {
        $files = glob(sys_get_temp_dir().'/jobs-tests/*/*.job');

        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function testAdd()
    {
        $job = $this->createJob();

        $this->assertTrue($this->storage->add('test', $job));
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->storage->count('test'));

        $this->storage->add('test', $this->createJob());
        $this->storage->add('test', $this->createJob());

        $this->assertEquals(2, $this->storage->count('test'));
    }

    public function testCountWithEmptyElements()
    {
        $this->assertEquals(0, $this->storage->count('test'));
    }

    public function testRetrieve()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->storage->add('test', $this->createJob());
        }

        $this->assertCount(2, $this->storage->retrieve('test', 2));
        $this->assertEquals(8, $this->storage->count('test'));
    }
}
