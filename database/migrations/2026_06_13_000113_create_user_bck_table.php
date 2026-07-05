<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_bck', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("role", 50);
            $table->string("nama", 60);
            $table->string("password", 11);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bck');
    }
};
