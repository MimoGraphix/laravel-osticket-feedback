<?php

namespace MimoGraphix\OSTicket;

use Illuminate\Support\Facades\App;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OSTicketServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot( )
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config' => base_path('config'),
            ], 'osticket-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/mimographix/laravel-osticket-feedback'),
            ], 'osticket-views');
        }

        Route::post('/send-ticket', '\MimoGraphix\OSTicket\Http\Controllers\OSTicketController@sendTicket')->name('osticket.send-ticket');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'osticket');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'osticket');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
