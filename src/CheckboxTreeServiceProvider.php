<?php

namespace GiocoPlus\CheckboxTree;

use Illuminate\Support\ServiceProvider;
use GiocoPlus\Admin\Admin;
use GiocoPlus\Admin\Form;

class CheckboxTreeServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(CheckboxTreeExtension $extension)
    {
        if (! CheckboxTreeExtension::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-checkboxtree');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/checkboxtree')],
                'laravel-admin-checkboxtree'
            );
        }

        Admin::booting(function () {
            // Admin::js('vendor/laravel-admin-ext/checkboxtree/dist/bootree.min.js');
            // Admin::css('vendor/laravel-admin-ext/checkboxtree/dist/bootree.min.css');
            Form::extend('checkboxtree', CheckboxTree::class);
        });

        $this->app->booted(function () {
            CheckboxTreeExtension::routes(__DIR__.'/../routes/web.php');
        });
    }
}
