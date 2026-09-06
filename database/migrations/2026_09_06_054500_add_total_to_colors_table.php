<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('colors', 'total')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->integer('total')->default(0)->after('supply');
            });

            // Inisialisasi nilai total awal dari hitungan pallet yang sudah ada (jika ada)
            try {
                DB::statement("UPDATE colors SET total = (SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '')");
            } catch (\Exception $e) {
                // Ignore if pallets table structure is different
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('colors', 'total')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->dropColumn('total');
            });
        }
    }
};
