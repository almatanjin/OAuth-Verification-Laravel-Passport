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
        Schema::table('washers', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->after('phone')->nullable();
            $table->decimal('longitude', 10, 7)->after('latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('washers', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            
        });
    }
};
