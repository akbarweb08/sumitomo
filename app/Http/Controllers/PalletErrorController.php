<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pallet;

class PalletErrorController extends Controller
{
    public function index()
    {
        $pallets = Pallet::join('colors', 'pallets.ColorId', '=', 'colors.Id')
            ->select('pallets.*', 'colors.InvoiceNumber')
            ->whereNotNull('pallets.errMsg')
            ->whereNull('pallets.DateOut')
            ->orderBy('pallets.LotNumber', 'ASC')
            ->orderByRaw('CAST(pallets.lineGroup AS SIGNED) ASC')
            ->orderBy('pallets.BoxNumber', 'ASC')
            ->get();

        $errorCount = count($pallets);

        return view('palleterror.index', compact('pallets', 'errorCount'));
    }
}
