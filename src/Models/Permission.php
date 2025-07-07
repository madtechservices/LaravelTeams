<?php

namespace Madtechservices\LaravelTeams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Config;
use Madtechservices\LaravelTeams\Support\Facades\Teams;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $table = 'team_permissions';
    protected $fillable = ['name', 'code'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable[] = Config::get('laravelteams.foreign_keys.team_id');
    }

    /**
     * Get all the groups that are assigned this permission.
     */
    public function groups(): MorphToMany
    {
        return $this->morphedByMany(Teams::model('group'), 'entity', 'team_entity_permission');
    }

    /**
     * Get all the roles that are assigned this permission.
     */
    public function roles(): MorphToMany
    {
        return $this->morphedByMany(Teams::model('role'), 'entity', 'team_entity_permission');
    }
}
