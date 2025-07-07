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
        Schema::create('team_abilities', static function (Blueprint $table) {
            $table->id();
            $table->foreignId(Config::get('laravelteams.foreign_keys.team_id', 'team_id'))->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('team_permissions')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->morphs('team_entity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_abilities');
    }
};
