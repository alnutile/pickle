<?php

namespace GD\Helpers;

trait BuildOutContent
{

    public function getParentLevelContent($parent_method_name) {
        return [
            "public function {$parent_method_name}() {",
            "}"
        ];
    }

    public function getStepLevelContent($step_method_name) {
        return [
            'method' => [
                "public function {$step_method_name}() {",
                "\$this->markTestIncomplete('Time to code');",
                "}"
            ],
            "reference" => sprintf("\$this->%s", $step_method_name)
        ];
    }

}