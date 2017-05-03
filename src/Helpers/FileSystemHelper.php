<?php

namespace GD\Helpers;

use GD\Exceptions\SourcePathNotFoundException;
use Symfony\Component\Finder\Finder;

trait FileSystemHelper
{

    protected $files = [];


    protected $source_path = null;

    /**
     * @var \Symfony\Component\Finder\Finder;
     */
    protected $finder = null;



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