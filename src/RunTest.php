<?php


namespace GD;

use Silly\Edition\Pimple\Application;

class RunTest extends BaseGherkinToDusk
{
    protected $path;

    /**
     * @var string
     */
    protected $class_file_name_and_path;
    

    public function handleDomain($path)
    {

        $this->path = $path;
        //Is DIR?
        
        //Is File
        $this->runTestFromFile();
    }

    private function setClassFileNameAndPath($class_file_name_and_path = null)
    {

        if (!$class_file_name_and_path) {
            $file_name = $this->app->getContainer()['filesystem']->name($this->path);

            $class_file_name_and_path = sprintf("tests/Unit/%sTest.php", camel_case($file_name));
        }

        $this->class_file_name_and_path = $class_file_name_and_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassFileNameAndPath()
    {
        return $this->class_file_name_and_path;
    }

    private function runTestFromFile()
    {
        $this->setClassFileNameAndPath();
    }
}
