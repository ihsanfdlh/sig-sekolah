<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->index(
                ['latitude', 'longitude'],
                'sekolahs_location_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropIndex('sekolahs_location_index');
        });
    }
};
