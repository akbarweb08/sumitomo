<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supply', function (Blueprint $table) {
            $table->string('prefix', 50)->nullable()->after('supplier');
            $table->text('address')->nullable()->after('prefix');
            $table->string('pic_name', 100)->nullable()->after('address');
            $table->string('contact', 50)->nullable()->after('pic_name');
            $table->string('email', 100)->nullable()->after('contact');
        });
    }

    public function down(): void
    {
        Schema::table('supply', function (Blueprint $table) {
            $table->dropColumn(['prefix', 'address', 'pic_name', 'contact', 'email']);
        });
    }
};
