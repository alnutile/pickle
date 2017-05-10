<?php

namespace GD\Helpers;

class WriteBrowserFile extends WriteFileBase
{

    public function writeTest($path, $name, $dusk_class_and_methods)
    {

        $this->write_class_name = $name;

        $this->write_destination_folder_path = $path;

        $this->checkDestinationTestFolder();

        $this->convertDuskClassAndMethodsArrayToText($dusk_class_and_methods);

        $this->saveToFile();
    }

    protected function addParentContent($parent_function)
    {
        $parent_base = __DIR__ . '/../../stubs/browser_parent.txt';
        $this->base = $this->getFilesystem()->get($parent_base);

        parent::addParentContent($parent_function);
    }

    protected function addSteps(array $steps)
    {
        $path = __DIR__ . '/../../stubs/browser_step.txt';
        $this->step_template = $this->getFilesystem()->get($path);

        parent::addSteps($steps);
    }

    protected function getAndSetHeaderArea()
    {
        $path = __DIR__ . '/../../stubs/browser_header.txt';
        $this->content = $this->getFilesystem()->get($path);

        parent::getAndSetHeaderArea();
    }

    protected function getAndSetFooterArea()
    {
        $path = __DIR__ . '/../../stubs/browser_footer.txt';
        $content = $this->getFilesystem()->get($path);

        $this->dusk_class_and_methods_string = $this->dusk_class_and_methods_string . $content;
    }
}
