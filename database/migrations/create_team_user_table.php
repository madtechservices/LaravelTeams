<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Config::get('laravelteams.tables.team_user', 'team_user'), static function (Blueprint $table) {
            $table->id();
            $table->foreignId(Config::get('laravelteams.foreign_keys.team_id', 'team_id'))->constrained(Config::get('laravelteams.tables.teams'))->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('role_id')->constrained('team_roles')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();

            $table->unique([Config::get('laravelteams.foreign_keys.team_id', 'team_id'), 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Config::get('laravelteams.tables.team_user', 'team_user'));
    }
};
