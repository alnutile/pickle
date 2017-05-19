<?php


namespace GD\Helpers;

class AppendFile extends AppendFileBase
{


    protected function addParentContent($parent_function)
    {
        $parent_base = __DIR__ . '/../../stubs/parent.txt';
        $this->base = $this->getFilesystem()->get($parent_base);

        parent::addParentContent($parent_function);
    }

    protected function addSteps(array $steps)
    {
        $path = __DIR__ . '/../../stubs/step.txt';
        $this->step_template = $this->getFilesystem()->get($path);

        parent::addSteps($steps);
    }
}
