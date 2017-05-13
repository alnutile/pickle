<?php


namespace GD\Tests;

use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use GD\GherkinToDusk;
use Illuminate\Filesystem\Filesystem;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{

    use CreateApplication;

    /**
     * @var Filesystem
     */
    protected $file;

    protected $default_test_type = 'domain';

    /**
     * @var \Silly\Edition\Pimple\Application
     */
    protected $app;

    /**
     * @var GherkinToDusk
     */
    protected $gd;

    protected function setUp()
    {
        $this->app = $this->createApplication();
        $this->gd = $this->app->getContainer()[\GD\GherkinToDusk::class];
        $this->file = $this->app->getContainer()[\Illuminate\Filesystem\Filesystem::class];

    }

    public function instantiateGD()
    {
         $this->file = new Filesystem();

        $il8n = __DIR__ . '/../bootstrap/i18n.yml';
        $keywords = new CucumberKeywords($il8n);
        $keywords->setLanguage('en');
        $lexer = new Lexer($keywords);
        $parser = new Parser($lexer);
        $this->gd = new \GD\GherkinToDusk($this->file, $parser);
    }


    protected function setupFolderAndFile()
    {
        $path = $this->gd->getSourcePath();

        /**
         * This can be come a path relative issue
         */
        if (!$this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777, true);
            $this->file->copy(
                $this->gd->getSourcePath() . '../fixtures/features/test_naming.feature',
                $this->gd->getSourcePath() . 'test_naming.feature'
            );
        }

        $path = $this->gd->setContext($this->default_test_type)->getDestinationFolderRoot();

        /**
         * This can be come a path relative issue
         */
        if (!$this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777, true);
        }
    }

    protected function cleanUpFile()
    {
        $path = $this->gd->getSourcePath();

        $paths[] = $path;

        $path = $this->gd->getDestinationFolderRoot();
        $paths[] = $path;

        foreach ($paths as $path) {
            $this->file->deleteDirectory($path);
        }
    }
}
