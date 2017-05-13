<?php


namespace GD\Tests;


trait CreateApplication
{
    /**
     * Creates the application.
     *
     * @return \Silly\Edition\Pimple\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        return $app;
    }
}