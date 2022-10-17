<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultCloudserverSettingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('cloudsettings')->insert(
            array(
                'default_io' => (int) 500,
                'default_cpu' => (int) 200,
                'default_swap' => (int) 0,
                'default_database' => (int) 1,
                'default_allocation' => (int) 1,
                'default_backup' => (int) 1,
                'min_memory' => (int) 512,
                'max_memory' => (int) 10240,
                'min_disk' => (int) 512,
                'max_disk' => (int) 10240,
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::table('cloudsettings')->where('id', '=', '1')->delete();
    }
}
