<?php

namespace EpicFailStudio\Core;

use EpicFailStudio\Core\Console\MakeListerCommand;
use EpicFailStudio\Core\Console\MakePresenterCommand;
use EpicFailStudio\Core\Console\MakeReleaseNoteCommand;
use EpicFailStudio\Core\Console\MakeTransformerCommand;
use EpicFailStudio\Core\Console\ImportCountriesCommand;
use EpicFailStudio\Core\Http\Middleware\Admin;
use EpicFailStudio\Core\Http\Middleware\AdminBarMiddleware;
use EpicFailStudio\Core\Repositories\CommandOutputRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CoreServiceProvider extends ServiceProvider
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
        }

        Route::post('/send-ticket', '\MimoGraphix\OSTicket\OSTicketController@sendTicket')->name('osticket.send-ticket');

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
