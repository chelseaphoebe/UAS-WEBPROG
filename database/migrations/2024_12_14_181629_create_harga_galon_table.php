<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('harga_galon', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->decimal('price', 8, 2); 
            $table->string('description');
            $table->json('benefit');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_galon');
    }
};
