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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('pn', 40)->nullable();
            $table->string('name')->nullable();
            $table->foreignId('facility_id')->nullable()->references('id')->on('facilities');
            $table->date('site_visit')->nullable();
            $table->date('bid_due')->nullable();
            $table->date('subcontractor_bid_due')->nullable();
            $table->string('bid_document')->nullable();
            $table->boolean('status')->default(0);
            $table->decimal('final_estimate', 12, 2)->nullable();
            $table->boolean('po_status')->default(0);
            $table->date('est_start_date')->nullable();
            $table->date('est_end_date')->nullable();
            $table->integer('duration')->nullable();
            $table->text('public_notes')->nullable();
            $table->text('internal_notes')->nullable();

            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();

            // $table->boolean('proposal_submitted')->nullable();
            // $table->foreignId('client_id')->references('id')->on('clients');
            $table->timestamps();
        });

        Schema::create('project_clients', function(Blueprint $table){
            $table->id();
            $table->foreignId('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->foreignId('client_id')->references('id')->on('companies')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_clients');
        Schema::dropIfExists('projects');
    }
};
