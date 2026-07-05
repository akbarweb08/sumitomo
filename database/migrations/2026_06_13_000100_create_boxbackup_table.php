<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boxbackup', function (Blueprint $table) {
            $table->integer("Id", true);
            $table->integer("BoxNumber");
            $table->string("LotNumber", 20);
            $table->string("lineGroup", 20);
            $table->integer("palletGroup");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxbackup');
    }
};
