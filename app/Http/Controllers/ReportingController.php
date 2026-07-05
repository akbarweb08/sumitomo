<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pallet;

class ReportingController extends Controller
{
    public function index()
    {
        $pallets = Pallet::join('colors', 'colors.Id', '=', 'pallets.ColorId')
            ->select('pallets.*', 'colors.InvoiceNumber', 'colors.ColorHex', 'colors.ColorText', 'colors.Prefiks')
            ->whereNotNull('pallets.DateOut')
            ->where('pallets.PalletNumber', '!=', '')
            ->orderBy('pallets.DateOut', 'DESC')
            ->get();

        return view('reporting.index', compact('pallets'));
    }
}
