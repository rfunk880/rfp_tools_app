<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('th_media')) {
            return;
        }
        Schema::create('th_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->nullable();
            $table->string('original_name')->nullable();
            $table->string('mime_type', 255)->nullable();
            $table->string('filesize', 255)->nullable();
            $table->string('folder', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media');
    }
}
