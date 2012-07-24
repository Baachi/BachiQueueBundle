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
            'table'       => 'jobs',
            'id_column'   => 'id',
            'data_column' => 'data',
            'date_column' => 'created_at',
        ));
    }

    public function count()
    {
        $query = $this->conn->query(sprintf(
            'SELECT COUNT(*) FROM %s',
            $this->conn->quoteIdentifier($this->options['table'])
        ));

        return $query->fetchColumn(0);
    }

    public function add(JobInterface $job)
    {
        $content = base64_encode(serialize($job));

        $this->conn->beginTransaction();
        try {
            $this->conn->insert($this->options['table'], array(
                $this->options['data_column'] => $content,
                $this->options['date_column'] => date(DATE_ISO8601),
            ));
        } catch (\PDOException $e) {
            $this->conn->rollback();
            throw $e;
        }

        return true;
    }

    public function retrieve($max)
    {
        $query = $this->conn->query(sprintf(
            'SELECT %s as id, %s as bin FROM %s ORDER BY %s DESC LIMIT %s',
            $this->conn->quoteIdentifier($this->options['id_column']),
            $this->conn->quoteIdentifier($this->options['data_column']),
            $this->conn->quoteIdentifier($this->options['table']),
            $this->conn->quoteIdentifier($this->options['date_column']),
            $max
        ));

        $jobs = array();
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $jobs[] = unserialize(base64_decode($row['bin']));
            $this->conn->delete($this->options['table'], array(
                $this->options['id_column'] => $row['id'],
            ));
        }

        return $jobs;
    }
}
