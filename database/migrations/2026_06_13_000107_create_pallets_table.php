<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pallets', function (Blueprint $table) {
            $table->integer("Id", true);
            $table->string("LotNumber", 11);
            $table->integer("BoxNumber");
            $table->integer("ColorId");
            $table->string("PalletNumber", 255);
            $table->integer("palletGroup");
            $table->string("lineGroup", 11);
            $table->dateTime("DateOut")->nullable();
            $table->string("ConfirmOut", 20);
            $table->dateTime("DateIn")->nullable();
            $table->string("ConfirmBy", 255);
            $table->string("returnTo", 11)->nullable();
            $table->text("errMsg")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pallets');
    }
};
