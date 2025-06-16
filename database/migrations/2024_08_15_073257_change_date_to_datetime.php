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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('site_visit');
            $table->dropColumn('bid_due');
            $table->dropColumn('subcontractor_bid_due');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dateTime('site_visit')->nullable();
            $table->dateTime('bid_due')->nullable();
            $table->dateTime('subcontractor_bid_due')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('site_visit');
            $table->dropColumn('bid_due');
            $table->dropColumn('subcontractor_bid_due');
        });
    }
};
