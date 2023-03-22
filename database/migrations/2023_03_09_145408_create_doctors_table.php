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
        Schema::create('doctors', function (Blueprint $table) {
            $table->string(column:'image');
            $table->string(column:'doctorname');
            $table->string(column:'degree');
            $table->string(column:'speciality');
            $table->string(column:'workplace');
            $table->string(column:'biography');
            $table->string(column:'experience');
            $table->string(column:'rating');
            $table->string(column:'appointmentNumber');
            $table->string(column:'location');
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
        Schema::dropIfExists('doctors');
    }
};
