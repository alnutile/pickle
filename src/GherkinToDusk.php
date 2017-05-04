<?php

namespace GD;

use Behat\Gherkin\Gherkin;
use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Loader\GherkinFileLoader;
use Behat\Gherkin\Loader\YamlFileLoader;
use Behat\Gherkin\Parser;
use GD\Exceptions\MustSetFileNameAndPath;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class GherkinToDusk extends BaseGherkinToDusk
{

    protected $component = false;

    protected $string_contents = null;

    /**
     * Yml Content of a test yml
     * @var array
     */
    protected $feature_content;

    /**
     * @var \Behat\Gherkin\Node\FeatureNode
     */
    protected $parsed_feature;

    /**
     *
     */
    protected $dusk_class_and_methods;

    protected $dusk_test_name;

    public function initializeFeature()
    {
        $this->loadFileContent();

        $this->buildDuskTestName();

        $this->passThroughParser();

        $this->breakIntoMethods();

        if ($this->context == 'domain') {
            $this->featureToUnit();
        }
    }

    protected function featureToUnit()
    {
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

    /**
     * @return mixed
     */
    public function getFeatureContent()
    {
        return $this->feature_content;
    }

    /**
     * @param mixed $feature_content
     */
    public function setFeatureContent($feature_content)
    {
        $this->feature_content = $feature_content;
    }

    private function loadFileContent()
    {
        $this->feature_content = $this->filesystem->get($this->getFullPathToFileAndFileName());
    }

    private function passThroughParser()
    {
        $this->parsed_feature = $this->getParser()->parse($this->feature_content);
    }

    /**
     * @return \Behat\Gherkin\Node\FeatureNode
     */
    public function getParsedFeature()
    {
        return $this->parsed_feature;
    }

    /**
     * @param \Behat\Gherkin\Node\FeatureNode $parsed_feature
     */
    public function setParsedFeature($parsed_feature)
    {
        $this->parsed_feature = $parsed_feature;
    }

    private function breakIntoMethods()
    {
        //take the parsed content and build out the methods needed for the file
        //1) the public method to test eg the Scenario
        //   set the name right

        $this->iterateOverScenariosAndBuildUpClassMethods();

        //2) and sub items it has eg the steps
        //   makesure their names are correct
    }

    private function iterateOverScenariosAndBuildUpClassMethods()
    {
        /** @var  $feature \Behat\Gherkin\Node\ScenarioNode */
        foreach ($this->parsed_feature->getScenarios() as $scenario_index => $scenario) {
            $parent_method_name = ucfirst(camel_case($scenario->getTitle()));

            $this->dusk_class_and_methods[$scenario_index] = [
                'parent' => sprintf('test%s', $parent_method_name)
            ];

            foreach ($scenario->getSteps() as $step_index => $step) {
                $method_name = camel_case(sprintf("%s %s", $step->getKeyword(), $step->getText()));
                $this->dusk_class_and_methods[$scenario_index]['steps'][$step_index] = $method_name;
            }
        }
    }

    private function buildDuskTestName()
    {
        if (!$this->dusk_test_name) {
            $name = $this->filesystem->name($this->getFullPathToFileAndFileName());
            $this->dusk_test_name = ucfirst(camel_case($name) . 'Test');
        }
    }

    private function getFullPathToFileAndFileName()
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->getPathToFeature();
    }

    /**
     * @return mixed
     */
    public function getDuskClassAndMethods()
    {
        return $this->dusk_class_and_methods;
    }

    /**
     * @param mixed $dusk_class_and_methods
     */
    public function setDuskClassAndMethods($dusk_class_and_methods)
    {
        $this->dusk_class_and_methods = $dusk_class_and_methods;
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
}
