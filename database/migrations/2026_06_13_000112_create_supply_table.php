<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("supplier", 100);
            $table->string("LotPlace", 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply');
    }
};
