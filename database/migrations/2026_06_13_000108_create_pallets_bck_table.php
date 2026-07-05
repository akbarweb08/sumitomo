<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pallets_bck', function (Blueprint $table) {
            $table->integer("Id", true);
            $table->string("LotNumber", 11);
            $table->integer("BoxNumber");
            $table->integer("ColorId");
            $table->string("PalletNumber", 255);
            $table->dateTime("DateOut")->nullable();
            $table->string("ConfirmBy", 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pallets_bck');
    }
};
