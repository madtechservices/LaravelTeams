<?php

namespace Madtechservices\LaravelTeams\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Madtechservices\LaravelTeams\Support\Services\TeamsService;

/**
 * @method static string model(string $model)
 * @method static object instance(string $model)
 *
 * @see TeamsService
 */
class Teams extends Facade
{
    /**
     * Gets the facade name.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return TeamsService::class;
    }
}
