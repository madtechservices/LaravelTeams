<?php

use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::middleware(Config::get('laravelteams.invitations.routes.middleware'))
    ->get(Config::get('laravelteams.invitations.routes.url'), [InviteController::class, 'inviteAccept'])
    ->name('teams.invitations.accept');
