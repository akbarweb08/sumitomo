<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recordcolors', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("LotPlace", 20);
            $table->string("Prefiks", 20);
            $table->integer("ColorId");
            $table->string("InvoiceNumber", 100);
            $table->string("ColorHex", 10);
            $table->string("ColorText", 10);
            $table->string("supply", 100);
            $table->date("recordDate")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recordcolors');
    }
};
