<?php


namespace GD\Helpers;

use GD\Exceptions\TestDoesNotFileExists;
use GD\Exceptions\TestFileExists;

abstract class AppendFileBase extends WriteFileBase
{
    protected $updated_content;

    public function writeTest($path, $name, $dusk_class_and_methods)
    {

        $this->write_class_name = $name;

        $this->write_destination_folder_path = $path;

        $this->checkDestinationTestFolder();

        $this->convertDuskClassAndMethodsArrayToText($dusk_class_and_methods);

        $this->createUpdatedContent();

        $this->appendToFile();

        $this->saveToFile();
    }



    protected function createUpdatedContent()
    {
        $where_is_the_end = strrpos($this->getExistingTestContent(), "}");

        $existing_content = $this->getExistingTestContent();

        $this->updated_content = $this->appendExistingFileWithNewResults($existing_content, $where_is_the_end);

        $this->dusk_class_and_methods_string = $this->updated_content;
    }

    protected function appendToFile()
    {

        $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . "\n}";
    }


    protected function convertDuskClassAndMethodsArrayToText($dusk_class_and_methods)
    {


        foreach ($dusk_class_and_methods as $dusk_class_and_method_index => $dusk_class_and_method) {
            if (!$this->checkIfMethodInClass($dusk_class_and_method)) {
                $this->addParentContent($dusk_class_and_method['parent']);
            }

            $this->addSteps($dusk_class_and_method['steps']);
        }
    }

    protected function checkDestinationTestFolder()
    {
        $file_to_append = $this->write_destination_folder_path . '/' . $this->write_class_name . '.php';

        if (!$this->getFilesystem()->exists($file_to_append)) {
            throw new TestDoesNotFileExists(sprintf("Could not find the file to append to at %s", $file_to_append));
        } else {
            $content = $this->getFilesystem()->get($file_to_append);
            $this->setExistingTestContent($content);
        }
    }

    protected function addNewStepToTest($step, $step_template)
    {
        $method = sprintf("protected function %s()", $step['method_name']);
        $found_existing = substr_count($this->getExistingTestContent(), $method);
        $found = substr_count($this->dusk_class_and_methods_string, $method);

        if ($found < 1 && $found_existing < 1) {
            $content = str_replace(["[STEP]", "[METHOD]"], $step['method_name'], $step_template);
            $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . $content;
        }
    }

    /**
     * @return mixed
     */
    public function getUpdatedContent()
    {
        return $this->updated_content;
    }

    protected function getAndSetFooterArea()
    {
        // TODO: Implement getAndSetFooterArea() method.
    }

    /**
     * @param $dusk_class_and_method
     * @return bool
     */
    protected function checkIfMethodInClass($dusk_class_and_method)
    {
        $parent = sprintf("function %s()", $dusk_class_and_method['parent']);

        return str_contains($this->getExistingTestContent(), $parent);
    }

    /**
     * @param $existing_content
     * @param $where_is_the_end
     * @return mixed
     */
    protected function appendExistingFileWithNewResults($existing_content, $where_is_the_end)
    {
        return substr_replace($existing_content, $this->dusk_class_and_methods_string, $where_is_the_end);
    }
}
