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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->references('id')->on('projects');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->boolean('type')->default(0);

            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('message_recepients', function(Blueprint $table){
            $table->id();
            $table->foreignId('message_id')->references('id')->on('messages')->cascadeOnDelete();
            $table->foreignId('contact_id')->references('id')->on('contacts')->cascadeOnDelete();
        });

        Schema::create('message_cc', function(Blueprint $table){
            $table->id();
            $table->foreignId('message_id')->references('id')->on('messages')->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_cc');
        Schema::dropIfExists('message_recepients');
        Schema::dropIfExists('messages');
    }
};
