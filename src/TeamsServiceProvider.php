<?php

namespace Madtechservices\LaravelTeams;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Madtechservices\LaravelTeams\Support\Services\TeamsService;
use Madtechservices\LaravelTeams\Middleware\Ability as AbilityMiddleware;
use Madtechservices\LaravelTeams\Middleware\Permission as PermissionMiddleware;
use Madtechservices\LaravelTeams\Middleware\Role as RoleMiddleware;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TeamsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravelteams.php', 'laravelteams');
    }

    /**
     * Bootstrap any application services.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'teams');

        $this->configureCommands();
        $this->configurePublishing();
        $this->registerFacades();
        $this->registerMiddlewares();

        if (Config::get('laravelteams.invitations.enabled') && Config::get('laravelteams.invitations.routes.register')) {
            $this->registerRoutes();
        }
    }

    /**
     * Configure publishing for the package.
     */
    protected function configurePublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $migrations = [
            __DIR__ . '/../database/migrations/create_teams_table.php' => database_path('migrations/2019_12_14_000001_create_teams_table.php'),
            __DIR__ . '/../database/migrations/create_team_permissions_table.php' => database_path('migrations/2019_12_14_000002_create_team_permissions_table.php'),
            __DIR__ . '/../database/migrations/create_team_roles_table.php' => database_path('migrations/2019_12_14_000003_create_team_roles_table.php'),
            __DIR__ . '/../database/migrations/create_team_user_table.php' => database_path('migrations/2019_12_14_000005_create_team_user_table.php'),
            __DIR__ . '/../database/migrations/create_team_abilities_table.php' => database_path('migrations/2019_12_14_000006_create_team_abilities_table.php'),
            __DIR__ . '/../database/migrations/create_team_entity_ability_table.php' => database_path('migrations/2019_12_14_000006_create_team_entity_ability_table.php'),
            __DIR__ . '/../database/migrations/create_team_groups_table.php' => database_path('migrations/2019_12_14_000008_create_team_groups_table.php'),
            __DIR__ . '/../database/migrations/create_team_group_user_table.php' => database_path('migrations/2019_12_14_000009_create_team_group_user_table.php'),
            __DIR__ . '/../database/migrations/create_team_entity_permission_table.php' => database_path('migrations/2019_12_14_000010_create_team_entity_permission_table.php'),
        ];

        if (Config::get('laravelteams.invitations.enabled')) {
            $migrations[__DIR__ . '/../database/migrations/create_team_invitations_table.php'] = database_path('migrations/2019_12_14_000012_create_team_invitations_table.php');
        }

        $this->publishes([
            __DIR__.'/../config/laravelteams.php' => config_path('laravelteams.php')
        ], 'laravelteams-config');

        $this->publishes($migrations, 'laravelteams-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/teams')
        ], 'laravelteams-views');
    }

    /**
     * Configure the commands offered by the application.
     */
    protected function configureCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([Console\InstallCommand::class]);
    }

    /**
     * Register the models offered by the application.
     *
     * @throws Exception
     */
    protected function registerFacades(): void
    {
        $this->app->singleton('teams', static function () {
            return new TeamsService();
        });
    }

    /**
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'middleware' => Config::get('laravelteams.invitations.routes.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the middlewares automatically.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function registerMiddlewares(): void
    {
        if (! $this->app['config']->get('laravelteams.middleware.register')) {
            return;
        }

        $middlewares = [
            'ability' => AbilityMiddleware::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ];

        foreach ($middlewares as $key => $class) {
            $this->app['router']->aliasMiddleware($key, $class);
        }
    }
}
