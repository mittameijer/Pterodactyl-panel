<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCloudserverSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cloudsettings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('systeem')->default(1);
            $table->string('default_cpu')->default(100);
            $table->string('default_swap')->default(0);
            $table->string('default_io')->default(500);
            $table->string('default_allocation')->default(0);
            $table->string('default_database')->default(0);
            $table->string('default_backup')->default(0);
            $table->string('max_memory')->default(0);
            $table->string('min_memory')->default(0);
            $table->string('min_disk')->default(0);
            $table->string('max_disk')->default(0);
            $table->string('totalservers')->default(0);
            $table->string('totalram')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cloudsettings');
    }
}
