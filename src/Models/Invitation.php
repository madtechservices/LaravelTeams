<?php

namespace Madtechservices\LaravelTeams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;
use Madtechservices\LaravelTeams\Support\Facades\Teams;

class Invitation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $table = 'team_invitations';
    protected $fillable = ['email', 'role_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable[] = Config::get('laravelteams.foreign_keys.team_id');
    }

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Teams::model('team'), Config::get('laravelteams.foreign_keys.team_id'));
    }

    /**
     * Accept the invitation to the team
     */
    public function accept()
    {
        // @todo: accept invitation
        // $this->team()->users()->attach($user, ['role' => $role]);
        // $invite->delete();
    }
}
