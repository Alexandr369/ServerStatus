<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('serverstatus_checks');
    }

    public function down(): void
    {
        Schema::create('serverstatus_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id');
            $table->string('status');
            $table->unsignedInteger('players')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamp('checked_at');

            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');

            $table->index(['server_id', 'checked_at']);
        });
    }
};
