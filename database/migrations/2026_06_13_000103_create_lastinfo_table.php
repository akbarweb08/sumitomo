<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lastinfo', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("LotPlace", 20);
            $table->dateTime("lastDate");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lastinfo');
    }
};
