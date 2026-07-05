<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colors_bck', function (Blueprint $table) {
            $table->integer("Id", true);
            $table->string("LotPlace", 11);
            $table->string("Prefiks", 20);
            $table->string("InvoiceNumber", 100);
            $table->string("ColorHex", 10);
            $table->string("ColorText", 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors_bck');
    }
};
