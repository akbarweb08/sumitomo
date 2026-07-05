<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Models\Boxbackup;
use App\Models\Pallet;

class HomeController extends Controller
{
    public function index()
    {
        $id = session('id');
        $role = session('role');
        
        // Query 1: Unconfirmed note - Sent
        $checkData = Note::where('status', 'Sent')->count('noteId');
        
        // Query 2: Unconfirmed note - User
        $checkData1 = Note::where('userId', $id)
            ->where('userId', $id)
            ->where('check', '!=', 'Check')
            ->where('status', '!=', 'Sent')
            ->count('noteId');
            
        // Query 3: Box Error
        $checkData2 = Boxbackup::from('boxbackup as A')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('pallets as B')
                      ->whereColumn('A.BoxNumber', 'B.BoxNumber')
                      ->whereColumn('A.LotNumber', 'B.LotNumber')
                      ->whereNull('B.DateOut');
            })->count('Id');

        $exGraceArray = ['242', '243', '244', '245'];

        // Pie Chart
        $pieData = Pallet::select('LotNumber', DB::raw('COUNT(*) as total'))
            ->whereNull('DateOut')
            ->where('ColorId', '!=', 2)
            ->where('PalletNumber', '!=', '')
            ->whereNotIn('LotNumber', $exGraceArray)
            ->groupBy('LotNumber')
            ->orderBy('LotNumber', 'ASC')
            ->get();

        $arrayPie = [];
        foreach ($pieData as $row) {
            $arrayPie[] = [$row->LotNumber, $row->total];
        }

        // Bar Chart - In
        $barInQuery = Pallet::select(DB::raw('MONTHNAME(DateIn) as newMonth'), DB::raw('COUNT(*) as total'))
            ->where('ColorId', '!=', 2)
            ->where('PalletNumber', '!=', '')
            ->whereNotIn('LotNumber', $exGraceArray)
            ->whereRaw('DateIn >= last_day(now()) + interval 1 day - interval 4 month')
            ->groupBy(DB::raw('MONTH(DateIn)'), DB::raw('MONTHNAME(DateIn)'))
            ->get();

        $arrayBar = [];
        $monthMap = [];
        foreach ($barInQuery as $index => $row) {
            $arrayBar[$index] = [$row->newMonth, $row->total, 0];
            $monthMap[$row->newMonth] = $index;
        }

        // Bar Chart - Out
        // The original code used MONTHNAME(DateIn) as newMonth but GROUP BY MONTH(DateOut). We fix this slightly to map correctly.
        $barOutQuery = Pallet::select(DB::raw('MONTHNAME(DateOut) as newMonth'), DB::raw('COUNT(*) as total'))
            ->whereNotNull('DateOut')
            ->where('ColorId', '!=', 2)
            ->where('PalletNumber', '!=', '')
            ->whereNotIn('LotNumber', $exGraceArray)
            ->whereRaw('DateOut >= last_day(now()) + interval 1 day - interval 4 month')
            ->groupBy(DB::raw('MONTH(DateOut)'), DB::raw('MONTHNAME(DateOut)'))
            ->get();

        foreach ($barOutQuery as $row) {
            if (isset($monthMap[$row->newMonth])) {
                $idx = $monthMap[$row->newMonth];
                $arrayBar[$idx][2] = $row->total;
            } else {
                $arrayBar[] = [$row->newMonth, 0, $row->total];
            }
        }

        return view('home', compact(
            'checkData', 'checkData1', 'checkData2', 'arrayPie', 'arrayBar'
        ));
    }
}
