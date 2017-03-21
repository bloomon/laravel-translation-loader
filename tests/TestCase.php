<?php

namespace Waavi\Translation\Test;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
        //$this->app['cache']->clear();

    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Waavi\Translation\TranslationServiceProvider::class,
        ];
    }

    /**
     * @param $app
     */
    protected function getPackageAliases($app)
    {
        return [
            'TranslationCache' => \Waavi\Translation\Facades\TranslationCache::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'sF5r4kJy5HEcOEx3NWxUcYj1zLZLHxuu');
        $app['config']->set('translator.source', 'api');
    }

}
