<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_lot_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('lot_place')->nullable();
            $table->string('nama_penanggung_jawab_gedung', 100)->nullable();
            $table->string('no_contact', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_lot_numbers');
    }
};
