<?php


namespace GD;

use Behat\Gherkin\Parser;
use Illuminate\Filesystem\Filesystem;
use Silly\Edition\Pimple\Application;

class BaseGherkinToDusk
{

    protected $features_folder = '/tests/features/';

    protected $source_path = null;

    protected $context = "domain";

    protected $path_to_feature = null;


    protected $dusk_test_name;

    /**
     * Conventions
     * file should be in tests/features for now
     * @var null
     */
    protected $file_name = null;

    protected $file_name_and_path = null;

    protected $destination_folder_root = null;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Parser
     */
    protected $parser;


    protected $output;

    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->filesystem = $this->app->getContainer()['filesystem'];

        $this->parser = $this->app->getContainer()['parser'];
    }


    public function getFileName()
    {
        return $this->file_name;
    }

    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
        return $this;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param null $filesystem
     * @return $this
     */
    public function setFilesystem($filesystem = null)
    {

        if (!$filesystem) {
            $filesystem = new Filesystem();
        }
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * @return null
     */
    public function getFileNameAndPath()
    {
        if ($this->file_name_and_path == null) {
            $this->setFileNameAndPath();
        }
        return $this->file_name_and_path;
    }

    /**
     * @param null $file_name_and_path
     * @return $this
     */
    public function setFileNameAndPath($file_name_and_path = null)
    {
        if (!$file_name_and_path) {
            $file_name_and_path =
                $this->getSourcePath() . $this->features_folder . $this->getFileName();
        }

        $this->file_name_and_path = $file_name_and_path;

        return $this;
    }



    /**
     * @return null
     */
    public function getSourcePath()
    {
        if ($this->source_path == null) {
            $this->setSourcePath();
        }
        return $this->source_path;
    }

    /**
     * @param null $source_path
     */
    public function setSourcePath($source_path = null)
    {
        if (!$source_path) {
            $source_path = getcwd() . '/tests/features/';
        }

        $this->source_path = $source_path;
    }

    /**
     * @return null
     */
    public function getDestinationFolderRoot()
    {
        if (!$this->destination_folder_root) {
            $this->setDestinationFolderRoot();
        }
        return $this->destination_folder_root;
    }

    /**
     * @param null $destination_folder_root
     */
    public function setDestinationFolderRoot($destination_folder_root = null)
    {
        if (!$destination_folder_root) {
            $destination_folder_root = getcwd() . sprintf('/tests/%s', $this->getContextFolder());
        }

        $this->destination_folder_root = $destination_folder_root;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param Parser $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    
    /**
     * @return null
     */
    public function getPathToFeature()
    {
        return $this->path_to_feature;
    }

    /**
     * @param null $path_to_feature
     * @return $this
     */
    public function setPathToFeature($path_to_feature)
    {
        $this->path_to_feature = $path_to_feature;
        return $this;
    }

    public function fullPathToDestinationFile()
    {
        return $this->getDestinationFolderRoot() . '/' . $this->getDuskTestName() . '.php';
    }


    protected function buildDuskTestName()
    {
        if (!$this->dusk_test_name) {
            $name = $this->getFilesystem()->name($this->getFullPathToFileAndFileName());
            $this->dusk_test_name = ucfirst(camel_case($name) . 'Test');
        }
    }

    protected function getFullPathToFileAndFileName()
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->getPathToFeature();
    }

    /**
     * @return mixed
     */
    public function getDuskTestName()
    {
        return $this->dusk_test_name;
    }

    /**
     * @param mixed $dusk_test_name
     */
    public function setDuskTestName($dusk_test_name)
    {
        $this->dusk_test_name = $dusk_test_name;
    }
    
    private function getContextFolder()
    {
        switch ($this->context) {
            case "domain":
                return "Feature";
            case "ui":
            case "browser":
                return "Browser";
            default:
                return "Feature";
        }
    }
}
