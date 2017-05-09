<?php

namespace GD\Helpers;

trait BuildOutContent
{

    public function getParentLevelContent($parent_method_name)
    {
        return [
            "method_name" => $parent_method_name
        ];
    }

    public function getStepLevelContent($step_method_name)
    {
        return [
            "method_name" => $step_method_name,
            "reference" => sprintf("\$this->%s", $step_method_name) . "()"
        ];
    }
}
