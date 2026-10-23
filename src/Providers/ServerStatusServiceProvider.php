<?php

namespace Azuriom\Plugin\ServerStatus\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\ServerStatus\Console\Commands\CheckServersCommand;
use Azuriom\Plugin\ServerStatus\Console\Commands\PurgeChecksCommand;
use Azuriom\Plugin\ServerStatus\Http\Middleware\SitewideBannerMiddleware;
use Illuminate\Console\Scheduling\Schedule;

class ServerStatusServiceProvider extends BasePluginServiceProvider
{
    /**
     * The plugin's global HTTP middleware stack.
     */
    protected array $middleware = [
        SitewideBannerMiddleware::class,
    ];

    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMiddlewares();
    }

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
                CheckServersCommand::class,
                PurgeChecksCommand::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);

                $schedule->command('serverstatus:check')->everyMinute();
                $schedule->command('serverstatus:purge')->daily();
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
