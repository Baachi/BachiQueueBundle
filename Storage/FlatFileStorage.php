<?php

namespace Bachi\QueueBundle\Storage;

use Bachi\QueueBundle\Model\JobInterface;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz
 */
class FlatFileStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * Constructor
     *
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        if (!is_dir($basePath) && !mkdir($basePath, 0777, true)) {
            throw new \RuntimeException(sprintf('Could not open directory %s', $basePath));
        }

        if (!is_writable($basePath)) {
            throw new \RuntimeException(sprintf('Directory %s is not writeable', $basePath));
        }

        $this->basePath = rtrim($basePath, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $iterator = new \RecursiveDirectoryIterator($this->basePath, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator = new \RegexIterator($iterator, '#\.job$#');

        return iterator_count($iterator);
    }

    /**
     * {@inheritDoc}
     */
    public function add(JobInterface $job)
    {
        $serialized = serialize($job);
        $filename   = $this->basePath.DIRECTORY_SEPARATOR.$this->calculateFilename($job);

        return (bool) file_put_contents($filename, $serialized);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve($max)
    {
        $iterator = new \RecursiveDirectoryIterator($this->basePath, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator = new \RegexIterator($iterator, '#\.job$#');

        $jobs = array();
        foreach ($iterator as $file) { /** @var $file \SplFileInfo */
            if ($max === 0) {
                break;
            }

            $jobs[] = unserialize(file_get_contents($file->getRealPath()));
            unlink($file->getRealPath());
            $max--;
        }

        return $jobs;
    }

    private function calculateFilename(JobInterface $job)
    {
        return strtolower(strtr(get_class($job), '\\', '-')).'@'.uniqid().'.job';
    }
}
