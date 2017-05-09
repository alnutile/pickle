<?php

namespace GD\Helpers;

use GD\Exceptions\TestFileExists;

trait WritePHPUnitFile
{

    protected $dusk_class_and_methods_string = "";
    protected $write_destination_folder_path = "";
    protected $write_class_name = "";

    public function writeUnitTest($path, $name, $dusk_class_and_methods)
    {

        $this->write_class_name = $name;

        $this->write_destination_folder_path = $path;

        $this->checkUnitFolder();

        $this->convertDuskClassAndMethodsArrayToText($dusk_class_and_methods);

        $this->saveToFile();
    }

    protected function checkUnitFolder()
    {

        if (!$this->getFilesystem()->exists($this->write_destination_folder_path)) {
            $this->getFilesystem()->makeDirectory($this->write_destination_folder_path, 0777, true);
        } else {
            if ($this->getFilesystem()->exists($this->write_destination_folder_path .
                $this->getDuskTestName() . '.php')) {
                throw new TestFileExists(sprintf("The file %s already exists", $this->getDuskTestName() . '.php'));
            }
        }
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

    protected function addParentContent($parent_function)
    {
        $parent_base = __DIR__ . '/../../stubs/parent.txt';
        $base = $this->getFilesystem()->get($parent_base);

        $base = str_replace("[PARENT_METHOD]", $parent_function, $base);

        $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . $base;
    }

    protected function addSteps(array $steps)
    {
        $references = "";
        $path = __DIR__ . '/../../stubs/step.txt';
        $step_template = $this->getFilesystem()->get($path);

        foreach ($steps as $step) {
            $references = $references . $step['reference'] . ";\n        ";
            $this->addNewStepToTest($step, $step_template);
        }

        $this->dusk_class_and_methods_string =
            str_replace("[STEPS_AREA]", $references, $this->dusk_class_and_methods_string);
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

    protected function getAndSetHeaderArea()
    {
        $path = __DIR__ . '/../../stubs/header.txt';
        $content = $this->getFilesystem()->get($path);

        $content = str_replace("[TEST_NAME]", $this->write_class_name, $content);
        $this->dusk_class_and_methods_string = $content;
    }

    protected function getAndSetFooterArea()
    {
        $path = __DIR__ . '/../../stubs/footer.txt';
        $content = $this->getFilesystem()->get($path);

        $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . $content;
    }

    public function getDuskClassAndMethodsString()
    {
        return $this->dusk_class_and_methods_string;
    }

    protected function saveToFile()
    {

        $this->getFilesystem()->put(
            sprintf("%s/%s.php", $this->write_destination_folder_path, $this->write_class_name),
            $this->dusk_class_and_methods_string
        );
    }
}
