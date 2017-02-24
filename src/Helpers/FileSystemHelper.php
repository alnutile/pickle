<?php

namespace GD\Helpers;

use GD\Exceptions\SourcePathNotFoundException;
use Symfony\Component\Finder\Finder;

trait FileSystemHelper
{

    protected $files = [];

    protected $file_name_and_path = null;

    protected $source_path = null;

    /**
     * @var \Symfony\Component\Finder\Finder;
     */
    protected $finder = null;

    /**
     * @return null
     */
    public function getFileNameAndPath()
    {
        return $this->file_name_and_path;
    }

    /**
     * @param null $file_name_and_path
     */
    public function setFileNameAndPath($file_name_and_path)
    {
        $this->file_name_and_path = $file_name_and_path;
    }


    /**
     * @return null
     */
    public function getSourcePath()
    {
        if($this->source_path == null) {
            $this->setSourcePath();
        }
        return $this->source_path;
    }

    /**
     * @param null $source_path
     */
    public function setSourcePath($source_path = null)
    {
        if(!$source_path) {
            $source_path = getcwd();
        }

        $this->source_path = $source_path;
    }

    private function getFiles()
    {
        if(!is_dir($this->source_path)) {
            throw new SourcePathNotFoundException(sprintf("Source path not found %s",
                $this->getSourcePath()));
        }

        $this->files = $this->getFinder()->in($this->source_path);

        return $this->files;

    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    public function getFinder() {
        if(!$this->finder) {
            $this->setFinder();
        }
        return $this->finder;
    }

    public function setFinder($finder = null) {
        if(!$finder) {
            $finder = new Finder();
        }

        $this->finder = $finder;
    }

}