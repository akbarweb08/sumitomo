<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Boxbackup;
use App\Models\Pallet;

class BoxErrorController extends Controller
{
    public function index()
    {
        $boxes = Boxbackup::from('boxbackup as A')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pallets as B')
                      ->whereColumn('A.BoxNumber', 'B.BoxNumber')
                      ->whereColumn('A.LotNumber', 'B.LotNumber')
                      ->whereNull('B.DateOut');
            })
            ->get();

        $errorCount = count($boxes);

        return view('boxerror.index', compact('boxes', 'errorCount'));
    }

    public function fix(Request $request)
    {
        if ($request->type == 'fixall') {
            $boxes = Boxbackup::from('boxbackup as A')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('pallets as B')
                          ->whereColumn('A.BoxNumber', 'B.BoxNumber')
                          ->whereColumn('A.LotNumber', 'B.LotNumber')
                          ->whereNull('B.DateOut');
                })
                ->get();

            foreach ($boxes as $box) {
                Pallet::insert([
                    'LotNumber' => $box->LotNumber,
                    'BoxNumber' => $box->BoxNumber,
                    'ColorId' => 1,
                    'PalletNumber' => '',
                    'palletGroup' => $box->palletGroup,
                    'lineGroup' => $box->lineGroup,
                    'DateOut' => null,
                    'ConfirmBy' => '',
                    'ConfirmOut' => ''
                ]);
            }
        } elseif ($request->type == 'fix') {
            Pallet::insert([
                'LotNumber' => $request->LotNumber,
                'BoxNumber' => $request->BoxNumber,
                'ColorId' => 1,
                'PalletNumber' => '',
                'palletGroup' => $request->palletGroup,
                'lineGroup' => $request->lineGroup,
                'DateOut' => null,
                'ConfirmBy' => '',
                'ConfirmOut' => ''
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
