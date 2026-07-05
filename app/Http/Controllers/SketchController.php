<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pallet;
use App\Models\Lastinfo;
use App\Models\Supply;
use App\Models\Color;
use App\Models\Receipt;

class SketchController extends Controller
{
    private function getSketchData($lotNumber, $lotPlace)
    {
        $box_array = array_fill(1, 1500, null);
        
        $pallets = Pallet::join('colors', 'colors.Id', '=', 'pallets.ColorId')
            ->where('LotNumber', $lotNumber)
            ->whereNull('DateOut')
            ->select('pallets.*', 'colors.Id as ColorId', 'colors.InvoiceNumber', 'colors.ColorHex', 'colors.ColorText', 'colors.Prefiks')
            ->get();
            
        $lastInvoice = null;
        if(count($pallets) > 0) {
            $lastInvoice = $pallets[count($pallets) - 1]->ColorId;
        }
        
        foreach($pallets as $row) {
            $box_array[$row->BoxNumber] = $row;
        }
        
        $lastinfo = Lastinfo::where('LotPlace', $lotPlace)->get();
        
        $supplies = Supply::select('supply.*', DB::raw("(SELECT COUNT(id) FROM colors WHERE colors.supply = supply.id AND colors.status != 'deleted' AND supply.LotPlace = colors.LotPlace) as total"))
            ->where('LotPlace', $lotPlace)
            ->whereRaw("(SELECT COUNT(id) FROM colors WHERE colors.supply = supply.id AND colors.status != 'deleted' AND supply.LotPlace = colors.LotPlace) != 0")
            ->orderBy('id', 'Desc')
            ->get();
        
        $colors_by_supplier = [];
        foreach($supplies as $sup) {
            if ($lotPlace == 'GRACE') {
                $colors = Color::select('colors.*', 
                    DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '' AND LotNumber = '243') as total"),
                    DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '' AND LotNumber = '244') as total2"),
                    DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '' AND LotNumber = '245') as total3")
                )->where('LotPlace', $lotPlace)->where('supply', $sup->id)->where('status', '!=', 'deleted')->orderBy('InvoiceNumber', 'ASC')->get();
            } else {
                $colors = Color::select('colors.*', 
                    DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '' AND LotNumber = ?) as total")
                )->addBinding($lotNumber, 'select')->where('LotPlace', $lotPlace)->where('supply', $sup->id)->where('status', '!=', 'deleted')->orderBy('InvoiceNumber', 'ASC')->get();
            }
            
            // Check receipt existence
            foreach($colors as &$color) {
                $check = Receipt::where('invoiceNumber', $color->InvoiceNumber)->count();
                $color->receipt_count = $check;
            }
            $sup->colors = $colors;
            $colors_by_supplier[$sup->id] = $colors;
        }
        
        // Modal data (from sketchfunction.php logic)
        $modalColors = Color::select('colors.*', DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '') as total"))
            ->where(function($q) use ($lotPlace) {
                $q->where('LotPlace', $lotPlace)->orWhere('LotPlace', '');
            })->where('status', '!=', 'deleted')->where('colors.Id', '!=', 1)->orderBy('supply', 'DESC')->get();

        $modalSuppliers = Supply::where('Lotplace', $lotPlace)->orderBy('supplier', 'ASC')->get();

        return compact('box_array', 'lastinfo', 'supplies', 'colors_by_supplier', 'lastInvoice', 'lotNumber', 'lotPlace', 'modalColors', 'modalSuppliers');
    }

    public function show($lot)
    {
        $lotPlace = $lot;
        if (in_array($lot, ['243', '244', '245', '242'])) {
            $lotPlace = 'GRACE';
        } elseif (in_array($lot, ['TURUNAN206', 'REPACK', '206'])) {
            $lotPlace = '206';
        }
        
        $data = $this->getSketchData($lot, $lotPlace);
        
        if (view()->exists("sketch.{$lot}")) {
            return view("sketch.{$lot}", $data);
        }
        
        abort(404, "View for Lot {$lot} not found.");
    }
}
