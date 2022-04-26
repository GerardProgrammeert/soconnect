<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('espresso_machines', function (Blueprint $table) {
            $table->id();
            $table->double('water_container_level',15,4)->default(2);
            $table->double('water_container_capacity',15,4)->default(2);
            $table->integer('beans_container_capacity')->default(50);
            $table->integer('beans_container_level')->default(50);
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
        Schema::dropIfExists('espresso_machines');
    }
};
