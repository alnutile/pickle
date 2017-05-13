<?php


use Behat\Gherkin\Keywords\CucumberKeywords;
use Behat\Gherkin\Lexer;
use Behat\Gherkin\Parser;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;



$app = new Silly\Edition\Pimple\Application();


$app->getContainer()[\Behat\Gherkin\Parser::class] = function() {
    $il8n = __DIR__ . '/i18n.yml';

    $keywords = new CucumberKeywords($il8n);

    $keywords->setLanguage('en');

    $lexer = new Lexer($keywords);

    $parser = new Parser($lexer);
    return $parser;
};


$app->getContainer()[Illuminate\Filesystem\Filesystem::class] = function() {
    return new \Illuminate\Filesystem\Filesystem();
};


$app->getContainer()[GD\GherkinToDusk::class] = function() use ($app) {
    $parser = $app->getContainer()[\Behat\Gherkin\Parser::class];
    $files  = $app->getContainer()[Illuminate\Filesystem\Filesystem::class];
    $gd = new GD\GherkinToDusk($files, $parser);

    return $gd;
};


return $app;