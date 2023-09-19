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
        Schema::create('consegnes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->default('posta');
            $table->string('sigla')->default('pos');
            $table->unsignedBigInteger('socio_id')->nullable(); 
            $table->timestamps();

            $table->foreign('socio_id')->references('id')->on('socis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consegnes');
    }
};
