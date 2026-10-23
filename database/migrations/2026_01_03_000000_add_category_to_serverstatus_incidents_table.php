<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serverstatus_incidents', function (Blueprint $table) {
            $table->string('category')->nullable()->after('type');
        });

        // Backfill existing rows: auto-detected outages become category "outage",
        // admin-created ones (previously all generic "maintenance") keep that
        // category too, since that was the only kind of manual incident so far.
        DB::table('serverstatus_incidents')->where('type', 'outage')->update(['category' => 'outage']);
        DB::table('serverstatus_incidents')->where('type', 'maintenance')->update(['category' => 'maintenance']);
    }

    public function down(): void
    {
        Schema::table('serverstatus_incidents', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
