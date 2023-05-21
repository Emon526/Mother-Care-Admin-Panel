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
            $table->string(column:'doctorId');
            $table->string(column:'image');
            $table->json('doctorname');
            $table->json('degree');
            $table->json('speciality');
            $table->json('workplace');
            $table->json('biography');
            $table->string(column:'experience');
            $table->string(column:'rating');
            $table->string(column:'appointmentNumber');
            $table->json('location');
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
