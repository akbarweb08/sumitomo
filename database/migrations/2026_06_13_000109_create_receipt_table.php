<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt', function (Blueprint $table) {
            $table->integer("id", true);
            $table->string("invoiceNumber", 50);
            $table->string("palletNumber", 50);
            $table->date("dateAdd")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt');
    }
};
