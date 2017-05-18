<?php


namespace GD\Helpers;

use GD\Exceptions\TestFileExists;
use Illuminate\Filesystem\Filesystem;

abstract class WriteFileBase
{

    protected $write_destination_folder_path = "";
    protected $dusk_class_and_methods_string = "";
    protected $write_class_name = "";
    protected $spacing = "";
    protected $existing_test_content;

    /**
     * @var Filesystem
     */
    protected $filesystem;
    protected $base;
    protected $step_template;
    protected $content;

    /**
     * @return string
     */
    public function getWriteClassName()
    {
        return $this->write_class_name;
    }


    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        if (!$this->filesystem) {
            $this->setFilesystem();
        }
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

    protected function convertDuskClassAndMethodsArrayToText($dusk_class_and_methods)
    {

        $this->getAndSetHeaderArea();

        foreach ($dusk_class_and_methods as $dusk_class_and_method_index => $dusk_class_and_method) {
            /**
             * Check if set
             */
            $this->addParentContent($dusk_class_and_method['parent']);

            $this->addSteps($dusk_class_and_method['steps']);
        }

        $this->getAndSetFooterArea();
    }

    public function getDuskClassAndMethodsString()
    {
        return $this->dusk_class_and_methods_string;
    }

    protected function getAndSetHeaderArea()
    {
        $content = str_replace("[TEST_NAME]", $this->write_class_name, $this->content);
        $this->dusk_class_and_methods_string = $content;
    }

    abstract protected function getAndSetFooterArea();

    protected function addSteps(array $steps)
    {
        $references = "";

        foreach ($steps as $index => $step) {
            $references = $references . $this->spacing . $step['reference'] . $this->notLastLine($steps, $index);
            $this->addNewStepToTest($step, $this->step_template);
        }

        $this->dusk_class_and_methods_string =
            str_replace("[STEPS_AREA]", $references, $this->dusk_class_and_methods_string);
    }

    protected function notLastLine(array $steps, $index)
    {
        if (($index + 1) < count($steps)) {
            return ";\n        ";
        }

        return ";";
    }


    protected function addParentContent($parent_function)
    {
        $base = str_replace("[PARENT_METHOD]", $parent_function, $this->base);

        $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . $base;
    }

    protected function addNewStepToTest($step, $step_template)
    {
        $method = sprintf("protected function %s()", $step['method_name']);
        $found = substr_count($this->dusk_class_and_methods_string, $method);

        if ($found < 1) {
            $content = str_replace("[STEP]", $step['method_name'], $step_template);
            $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . $content;
        }
    }

    protected function checkDestinationTestFolder()
    {

        if (!$this->getFilesystem()->exists($this->write_destination_folder_path)) {
            $this->getFilesystem()->makeDirectory($this->write_destination_folder_path, 0777, true);
        } else {
            if ($this->getFilesystem()->exists($this->write_destination_folder_path .
                $this->write_class_name . '.php')) {
                throw new TestFileExists(sprintf("The file %s already exists", $this->write_class_name . '.php'));
            }
        }
    }

    protected function saveToFile()
    {
        $this->getFilesystem()->put(
            sprintf("%s/%s.php", $this->write_destination_folder_path, $this->write_class_name),
            $this->dusk_class_and_methods_string
        );
    }

    public function getExistingTestContent()
    {

        if (!$this->existing_test_content) {
            $this->setExistingTestContent();
        }
        
        return $this->existing_test_content;
    }

    /**
     * @param mixed $existing_test_content
     */
    public function setExistingTestContent($existing_test_content = null)
    {
        if (!$existing_test_content) {
            $file_to_append = $this->write_destination_folder_path . '/' . $this->write_class_name . '.php';
            $content = $this->getFilesystem()->get($file_to_append);
            $this->existing_test_content = $content;
        }
        
        $this->existing_test_content = $existing_test_content;
    }
}
