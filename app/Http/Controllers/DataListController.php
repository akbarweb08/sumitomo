<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pallet;

class DataListController extends Controller
{
    public function index()
    {
        $pallets = Pallet::join('colors', 'colors.Id', '=', 'pallets.ColorId')
            ->select('pallets.*', 'colors.InvoiceNumber', 'colors.ColorHex', 'colors.ColorText', 'colors.Prefiks')
            ->whereNull('pallets.DateOut')
            ->where('pallets.PalletNumber', '!=', '')
            ->where('pallets.ColorId', '!=', 2)
            ->orderBy('pallets.DateIn', 'DESC')
            ->get();

        return view('datalist.index', compact('pallets'));
    }
}
