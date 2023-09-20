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
        Schema::create('associatis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('anagrafica_id'); 
            $table->foreign('anagrafica_id')->references('id')->on('anagraficas'); 

            $table->unsignedBigInteger('ruoli_id'); 
            $table->foreign('ruoli_id')->references('id')->on('ruolis'); 

            $table->unsignedBigInteger('ruoli_spec_id'); 
            $table->foreign('ruoli_spec_id')->references('id')->on('ruoli_specs'); 

            $table->unsignedBigInteger('dateiscr_id'); 
            $table->foreign('dateiscr_id')->references('id')->on('dateiscrs'); 
            $table->string('nome')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associatis');
    }
};
