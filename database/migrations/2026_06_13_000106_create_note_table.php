<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note', function (Blueprint $table) {
            $table->integer("noteId", true);
            $table->unsignedBigInteger("userId");
            $table->dateTime("noteDate")->nullable();
            $table->text("message");
            $table->string("status", 50);
            $table->text("reply");
            $table->string("check", 5);
            $table->foreign("userId")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note');
    }
};
