<?php

namespace Hsy\LaravelAdminLte;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Hsy\LaravelAdminLte\Console\AdminLteInstallCommand;
use Hsy\LaravelAdminLte\Console\AdminLtePluginCommand;
use Hsy\LaravelAdminLte\Console\AdminLteStatusCommand;
use Hsy\LaravelAdminLte\Console\AdminLteUpdateCommand;
use Hsy\LaravelAdminLte\Events\BuildingMenu;
use Hsy\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;

class AdminLteServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdminLte::class, function (Container $app) {
            return new AdminLte(
                $app['config']['adminlte.filters'],
                $app['events'],
                $app
            );
        });
    }

    public function boot(
        Factory $view,
        Dispatcher $events,
        Repository $config
    ) {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadConfig();

        $this->registerCommands();

        $this->registerViewComposers($view);

        static::registerMenu($events, $config);
    }

    private function loadViews()
    {
        $viewsPath = $this->packagePath('resources/views');
        $this->loadViewsFrom($viewsPath, 'adminlte');
    }

    private function loadTranslations()
    {
        $translationsPath = $this->packagePath('resources/lang');

        $this->loadTranslationsFrom($translationsPath, 'adminlte');
    }

    private function loadConfig()
    {
        $configPath = $this->packagePath('config/adminlte.php');

        $this->mergeConfigFrom($configPath, 'adminlte');
    }

    private function packagePath($path)
    {
        return __DIR__."/../$path";
    }

    private function registerCommands()
    {
        $this->commands(AdminLteInstallCommand::class);
        $this->commands(AdminLteStatusCommand::class);
        $this->commands(AdminLteUpdateCommand::class);
        $this->commands(AdminLtePluginCommand::class);
    }

    private function registerViewComposers(Factory $view)
    {
        $view->composer('adminlte::page', AdminLteComposer::class);
    }

    public static function registerMenu(Dispatcher $events, Repository $config)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) use ($config) {
            $menu = $config->get('adminlte.menu');
            call_user_func_array([$event->menu, 'add'], $menu);
        });
    }
}
