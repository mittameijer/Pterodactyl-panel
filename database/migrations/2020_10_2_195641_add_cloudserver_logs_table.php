<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloudserverLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cloudlogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator');
            $table->string('type');
            $table->string('name');
            $table->string('memory');
            $table->string('disk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cloudeggs');
    }
}
