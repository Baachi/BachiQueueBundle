<?php

namespace Bachi\QueueBundle\Tests\Storage;

use Bachi\QueueBundle\Tests\TestCase;
use Bachi\QueueBundle\Storage\DBALStorage;

use Doctrine\DBAL\DriverManager;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class DBALStorageTest extends TestCase
{
    /**
     * @var DBALStorage
     */
    private $storage;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private static $conn;

    public static function setUpBeforeClass()
    {
        if (!class_exists('Doctrine\\DBAL\\Connection')) {
            self::markTestSkipped('Doctrine DBAL not installed');
        }

        /** @var $conn \Doctrine\DBAL\Connection */
        $conn = DriverManager::getConnection(array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ));

        $sm = new \Doctrine\DBAL\Schema\Schema();
        $table = $sm->createTable('jobs');

        $table->addColumn('id', 'integer');
        $table->addColumn('data', 'blob');
        $table->addColumn('queue_name', 'string');
        $table->addColumn('created_at', 'date');
        $table->setPrimaryKey(array('id'));

        foreach ($conn->getDatabasePlatform()->getCreateTableSQL($table) as $sql) {
            $conn->exec($sql);
        }

        static::$conn = $conn;
    }

    protected function setup()
    {
        $this->storage = new DBALStorage(static::$conn);
    }

    protected function tearDown()
    {
        static::$conn->exec('DELETE FROM jobs');
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

    public function testRetrieve()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->storage->add('test', $this->createJob());
        }

        $this->assertCount(2, $this->storage->retrieve('test', 2));
        $this->assertEquals(8, $this->storage->count('test'));
    }

    public function testCountWithEmptyElements()
    {
        $this->assertEquals(0, $this->storage->count('test'));
    }
}
