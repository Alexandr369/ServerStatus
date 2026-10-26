<?php

namespace Azuriom\Plugin\ServerStatus\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\ServerStatus\Console\Commands\SyncServersCommand;
use Illuminate\Console\Scheduling\Schedule;

class ServerStatusServiceProvider extends BasePluginServiceProvider
{
    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        Permission::registerPermissions([
            'serverstatus.manage' => 'serverstatus::admin.permissions.manage',
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncServersCommand::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);

                $schedule->command('serverstatus:sync')->everyMinute();
            });
        }
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array
     */
    protected function routeDescriptions()
    {
        return [
            'serverstatus.index' => trans('serverstatus::messages.title'),
        ];
    }

    /**
     * Return the admin navigation routes to register in the dashboard.
     *
     * @return array
     */
    protected function adminNavigation()
    {
        return [
            'serverstatus' => [
                'name' => trans('serverstatus::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-broadcast',
                'route' => 'serverstatus.admin.*',
                'items' => [
                    'serverstatus.admin.incidents' => [
                        'name' => trans('serverstatus::admin.nav.incidents'),
                        'permission' => 'serverstatus.manage',
                    ],
                    'serverstatus.admin.settings' => [
                        'name' => trans('serverstatus::admin.nav.settings'),
                        'permission' => 'serverstatus.manage',
                    ],
                ],
            ],
        ];
    }
}
