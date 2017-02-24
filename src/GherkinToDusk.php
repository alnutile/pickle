<?php

namespace GD;

use GD\Exceptions\MustSetFileNameAndPath;
use GD\Helpers\FileSystemHelper;

class GherkinToDusk
{
    use FileSystemHelper;

    protected $component = false;

    protected $string_contents = null;

    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
        // constructor body
    }

    public function handle() {

        $this->getSourcePath();

        $this->getFiles();

        if($this->component == 'initialize-file') {
            $this->initializeFile();
        } else {
            //just run them all
        }

    }

    private function initializeFile()
    {
        //Should have file_name set in the path to file
        if(!$this->file_name_and_path) {
            throw new MustSetFileNameAndPath(sprintf("Must set file and path for .feature to initialize"));
        }
        //should now have file (one) in array
        //and should read that .feature file
        //and turn it into stubbed php unit test
        //based on the type domain or unit
    }

    /**
     * @return boolean
     */
    public function isComponent()
    {
        return $this->component;
    }

    /**
     * @param boolean $component
     */
    public function setComponent($component)
    {
        $this->component = $component;
    }

}
