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
    /**
     * @var Filesystem
     */
    protected $file;

    /**
     * @var GherkinToDusk
     */
    protected $gd;

    public function instantiateGD() {
        $this->file = new Filesystem();
        $il8n = __DIR__ . '/../src/i18n.yml';
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
    }

    protected function cleanUpFile()
    {
        $path = $this->gd->getSourcePath();

        /**
         * This can be come a path relative issue
         */
        if ($this->file->exists($path)) {
            $this->file->deleteDirectory($path);
        }
    }

}
