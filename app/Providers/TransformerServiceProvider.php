<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TransformerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Transformer', function($app, $attributes){
            return $this->transformerMap()[$attributes['type']][$attributes['module']];
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function transformerMap(): array
    {
        return array(
            'selection' => array(
                'prototype' => \App\Transformers\SelectionTransformer::class
            )
        );
    }
}
