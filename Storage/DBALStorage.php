<?php

namespace Bachi\QueueBundle\Storage;

use Doctrine\DBAL\Connection;
use Bachi\QueueBundle\Model\JobInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class DBALStorage implements StorageInterface
{
    private $conn;

    private $options;

    public function __construct(Connection $conn, array $options = array())
    {
        $this->conn = $conn;

        $this->options = array_merge(array(
            'table'        => 'jobs',
            'id_column'    => 'id',
            'queue_column' => 'queue_name',
            'data_column'  => 'data',
            'date_column'  => 'created_at',
        ));
    }

    public function count($name)
    {
        $stmt = $this->conn->prepare(sprintf(
            'SELECT COUNT(*) FROM %s j WHERE j.%s = ?',
            $this->conn->quoteIdentifier($this->options['table']),
            $this->conn->quoteIdentifier($this->options['queue_column'])
        ));

        $stmt->execute(array($name));

        return $stmt->fetchColumn(0);
    }

    public function add($name, JobInterface $job)
    {
        $content = base64_encode(serialize($job));

        $this->conn->beginTransaction();
        try {
            $this->conn->insert($this->options['table'], array(
                $this->options['data_column']  => $content,
                $this->options['queue_column'] => $name,
                $this->options['date_column']  => date(DATE_ISO8601),
            ));
        } catch (\PDOException $e) {
            $this->conn->rollback();
            throw $e;
        }

        return true;
    }

    public function retrieve($name, $max)
    {
        $stmt = $this->conn->prepare(sprintf(
            'SELECT %s as id, %s as bin FROM %s WHERE %s = ? ORDER BY %s DESC LIMIT %s',
            $this->conn->quoteIdentifier($this->options['id_column']),
            $this->conn->quoteIdentifier($this->options['data_column']),
            $this->conn->quoteIdentifier($this->options['table']),
            $this->conn->quoteIdentifier($this->options['queue_column']),
            $this->conn->quoteIdentifier($this->options['date_column']),
            $max
        ));

        $stmt->execute(array($name));

        $jobs = array();
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $jobs[] = unserialize(base64_decode($row['bin']));
            $this->conn->delete($this->options['table'], array(
                $this->options['id_column'] => $row['id'],
            ));
        }

        return $jobs;
    }
}
