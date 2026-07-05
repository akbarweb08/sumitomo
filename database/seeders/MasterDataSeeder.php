<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Add Default Colors for logic (ColorId 1 and 2 usually have special meanings in this app)
        \App\Models\Color::insert([
            ['Id' => 1, 'LotPlace' => '', 'Prefiks' => '', 'InvoiceNumber' => 'DUMMY_COLOR_1', 'ColorHex' => '#FFFFFF', 'ColorText' => 'White', 'status' => 'active', 'supply' => '0'],
            ['Id' => 2, 'LotPlace' => '', 'Prefiks' => '', 'InvoiceNumber' => 'DUMMY_COLOR_2', 'ColorHex' => '#000000', 'ColorText' => 'Black', 'status' => 'active', 'supply' => '0'],
        ]);

        // 2. Add some Suppliers
        $supplies = [
            ['supplier' => 'PT. Supplier A', 'LotPlace' => 'GRACE'],
            ['supplier' => 'PT. Supplier B', 'LotPlace' => '206'],
        ];
        
        foreach ($supplies as $supply) {
            $supModel = \App\Models\Supply::create($supply);
            
            // 3. Add Colors for each Supplier
            \App\Models\Color::insert([
                [
                    'LotPlace' => $supply['LotPlace'],
                    'Prefiks' => 'PRFX-' . $supModel->id . '-1',
                    'InvoiceNumber' => 'INV-' . $supModel->id . '-1000',
                    'ColorHex' => '#FF0000',
                    'ColorText' => 'Red',
                    'status' => 'active',
                    'supply' => (string)$supModel->id
                ],
                [
                    'LotPlace' => $supply['LotPlace'],
                    'Prefiks' => 'PRFX-' . $supModel->id . '-2',
                    'InvoiceNumber' => 'INV-' . $supModel->id . '-2000',
                    'ColorHex' => '#00FF00',
                    'ColorText' => 'Green',
                    'status' => 'active',
                    'supply' => (string)$supModel->id
                ]
            ]);
        }
    }
}
