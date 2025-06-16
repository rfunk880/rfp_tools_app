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
        Schema::table('project_clients', function (Blueprint $table) {
            $table->json('primary_contacts')->nullable();
        });
        Schema::table('project_companies', function (Blueprint $table) {
            $table->json('primary_contacts')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_clients', function (Blueprint $table) {
            $table->dropColumn('primary_contacts');
        });
        Schema::table('project_companies', function (Blueprint $table) {
            $table->dropColumn('primary_contacts');
        });
    }
};
