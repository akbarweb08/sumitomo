<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Record;
use App\Models\Recordcolor;

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

    public function viewRecordSketch(Request $request, $lot, $date)
    {
        $lotPlace = $lot;
        if (in_array($lot, ['243', '244', '245', '242'])) {
            $lotPlace = 'GRACE';
        } elseif (in_array($lot, ['TURUNAN206', 'REPACK', '206'])) {
            $lotPlace = '206';
        }

        $box_array = array_fill(1, 1500, null);

        // Fetch records and recordcolors for the specific date
        $pallets = Record::join('recordcolors', 'recordcolors.Id', '=', 'record.ColorId')
            ->where('record.LotNumber', $lot)
            ->where('record.Date', $date)
            ->select('record.*', 'recordcolors.Id as ColorId', 'recordcolors.InvoiceNumber', 'recordcolors.ColorHex', 'recordcolors.ColorText', 'recordcolors.Prefiks')
            ->get();

        $lastInvoice = null;
        if (count($pallets) > 0) {
            $lastInvoice = $pallets[count($pallets) - 1]->ColorId;
        }

        foreach ($pallets as $row) {
            $box_array[$row->BoxNumber] = $row;
        }

        $lastinfo = []; // Not needed for read-only
        $supplies = collect([]); // Empty for read-only
        $colors_by_supplier = [];
        $modalColors = collect([]);
        $modalSuppliers = collect([]);
        $lotNumber = $lot;
        $isRecord = true;
        $recordDate = $date;

        if (view()->exists("sketch.{$lot}")) {
            return view("sketch.{$lot}", compact(
                'box_array', 'lastinfo', 'supplies', 'colors_by_supplier', 
                'lastInvoice', 'lotNumber', 'lotPlace', 'modalColors', 
                'modalSuppliers', 'isRecord', 'recordDate'
            ));
        }

        abort(404, "View for Lot {$lot} not found.");
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
