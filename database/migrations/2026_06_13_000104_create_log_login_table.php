<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_login', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("nama", 50);
            $table->dateTime("dateTime");
            $table->string("action", 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_login');
    }
};
