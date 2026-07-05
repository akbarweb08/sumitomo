<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Record;

class RecordedDataController extends Controller
{
    public function index()
    {
        $records = Record::select('LotNumber', DB::raw('MONTH(Date) as MonthDate'))
            ->distinct()
            ->orderBy('Date', 'DESC')
            ->orderBy('LotNumber', 'ASC')
            ->get();

        return view('recordeddata.index', compact('records'));
    }

    public function show(Request $request)
    {
        $lot = $request->lot;
        $date = $request->date; // This is the month number

        $details = Record::select('LotNumber', 'Date', 'exactDate')
            ->distinct()
            ->whereRaw('MONTH(Date) = ?', [$date])
            ->where('LotNumber', $lot)
            ->orderBy('Date', 'DESC')
            ->get();

        return view('recordeddata.show', compact('details', 'lot', 'date'));
    }

    public function destroy(Request $request)
    {
        $lot = $request->LotNumber;
        $date = $request->Date;

        Record::where('LotNumber', $lot)
            ->where('Date', $date)
            ->delete();

        return response()->json(['status' => 'success']);
    }
}
