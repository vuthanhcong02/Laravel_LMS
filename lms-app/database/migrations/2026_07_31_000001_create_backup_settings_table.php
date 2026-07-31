<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->enum('frequency', ['hourly', 'daily', 'weekly'])->default('daily');
            $table->time('run_at')->default('02:00:00');
            $table->tinyInteger('day_of_week')->default(0);
            $table->unsignedInteger('max_backups')->default(7);
            $table->timestamps();
        });

        DB::table('backup_settings')->insert([
            'enabled'     => false,
            'frequency'   => 'daily',
            'run_at'      => '02:00:00',
            'day_of_week' => 0,
            'max_backups' => 7,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_settings');
    }
};
