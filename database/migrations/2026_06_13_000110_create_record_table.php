<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('record', function (Blueprint $table) {
            $table->integer("Id", true);
            $table->string("LotNumber", 20);
            $table->integer("BoxNumber");
            $table->integer("ColorId");
            $table->string("PalletNumber", 255);
            $table->date("Date");
            $table->dateTime("exactDate")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('record');
    }
};
