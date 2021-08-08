<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_apps', function (Blueprint $table) {
            $table->unsignedBigInteger('device_id');
            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onDelete('cascade');
            $table->integer('app_id');
            $table->string('token');
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
        Schema::dropIfExists('device_apps');
    }
}
