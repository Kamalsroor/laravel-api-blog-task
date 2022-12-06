<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FacadesValidator::extend('phone_number', function($attribute, $value, $parameters)
        {
            return substr($value, 0, 2) == '01';
        });

        $this->registerRepositories();


    }


            /**
     * Register views.
     *
     * @return void
     */
    public function registerRepositories()
    {

        $toBind = [
            \App\Interfaces\TagRepositoryInterface::class => \App\Repositories\TagRepository::class,
            \App\Interfaces\PostRepositoryInterface::class => \App\Repositories\PostRepository::class,
            // All repositories are registered in this map
        ];

        foreach ($toBind as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

}

