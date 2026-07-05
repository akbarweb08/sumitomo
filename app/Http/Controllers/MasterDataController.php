<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Color;
use App\Models\Supply;
use App\Models\Pallet;

class MasterDataController extends Controller
{
    public function index()
    {
        $lot = session('permit');
        
        $query = Color::query()
            ->select('colors.*', DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '') as total"))
            ->leftJoin('supply', 'colors.supply', '=', 'supply.id')
            ->select('colors.*', 'supply.supplier as supply_name', DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '') as total"))
            ->where('colors.id', '>', 2)
            ->where('colors.status', '!=', 'deleted');

        if ($lot == 'super') {
            // no additional filters
        } else if ($lot == 'GRACE') {
            $query->whereIn('colors.LotPlace', ['GRACE', '242']);
        } else if ($lot == '206') {
            $query->whereIn('colors.LotPlace', ['206', 'TURUNAN206', 'REPACK']);
        } else {
            $query->where('colors.LotPlace', $lot);
        }

        $colors = $query->orderBy('colors.id', 'DESC')->get();

        // For bottom table (not the lot)
        $queryOther = Color::query()
            ->select('colors.*', 'supply.supplier as supply_name', DB::raw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '') as total"))
            ->leftJoin('supply', 'colors.supply', '=', 'supply.id')
            ->where('colors.id', '>', 2)
            ->where('colors.status', '!=', 'deleted');

        if ($lot != 'super') {
            if ($lot == 'GRACE') {
                $queryOther->whereNotIn('colors.LotPlace', ['GRACE', '242']);
            } else if ($lot == '206') {
                $queryOther->whereNotIn('colors.LotPlace', ['206', 'TURUNAN206', 'REPACK']);
            } else {
                $queryOther->where('colors.LotPlace', '!=', $lot);
            }
            $colorsOther = $queryOther->orderBy('colors.id', 'DESC')->get();
        } else {
            $colorsOther = [];
        }

        // Supply Dropdown
        $querySupply = Supply::query();
        if ($lot == 'super') {
            // all
        } else if ($lot == 'GRACE') {
            $querySupply->where('LotPlace', 'GRACE');
        } else if ($lot == '206') {
            $querySupply->whereIn('LotPlace', ['206', 'TURUNAN206', 'REPACK']);
        } else {
            $querySupply->where('LotPlace', $lot);
        }
        $supplies = $querySupply->orderBy('id', 'ASC')->get();

        return view('masterdata.index', compact('colors', 'colorsOther', 'supplies', 'lot'));
    }

    public function store(Request $request)
    {
        $LotPlace = $request->LotPlace;
        $Prefiks = $request->Prefiks;
        $InvoiceNumber = $request->InvoiceNumber;
        $Supplier = $request->SupplyName;
        $ColorHex = $request->ColorHex;
        $ColorText = $request->ColorText;

        if ($request->type == 'loc') {
            if (in_array($request->LotPlace, [243, 244, 245])) {
                $LotPlace = 'GRACE';
            }
        }

        if (empty($Supplier)) {
            $supply = Supply::where('LotPlace', $LotPlace)->orderBy('supplier', 'ASC')->first();
            if ($supply) {
                $Supplier = $supply->id;
            } else {
                $Supplier = 0; // fallback if no supply exists
            }
        }

        $exists = Color::where('LotPlace', $LotPlace)
            ->where('InvoiceNumber', $InvoiceNumber)
            ->where('Prefiks', $Prefiks)
            ->where('status', '!=', 'deleted')
            ->exists();

        if ($exists) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Maaf data yang anda masukkan sama', 'status' => 'error']);
            }
            return back()->with('error', 'Data yang anda masukkan sama atau sudah ada.');
        }

        Color::insert([
            'LotPlace' => $LotPlace,
            'Prefiks' => $Prefiks,
            'InvoiceNumber' => $InvoiceNumber,
            'ColorHex' => $ColorHex,
            'ColorText' => $ColorText,
            'status' => '',
            'supply' => $Supplier
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Berhasil', 'status' => 'success']);
        }

        if ($request->type == 'loc' && $request->has('loc')) {
            return redirect($request->loc)->with('success', 'Berhasil tambah data');
        }

        return redirect()->route('masterdata.index')->with('success', 'Berhasil tambah data');
    }

    public function update(Request $request)
    {
        $Id = $request->Id;
        $LotPlace = $request->LotPlace;
        $Prefiks = $request->Prefiks;
        $InvoiceNumber = $request->InvoiceNumber;
        $ColorHex = $request->ColorHex;
        $ColorText = $request->ColorText;
        $Supplier = $request->supplyEdit;

        $exists = Color::where('LotPlace', $LotPlace)
            ->where('InvoiceNumber', $InvoiceNumber)
            ->where('Id', '!=', $Id)
            ->where('status', '!=', 'deleted')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Maaf data yang anda masukkan sama', 'status' => 'error']);
        }

        Color::where('Id', $Id)->update([
            'InvoiceNumber' => $InvoiceNumber,
            'ColorHex' => $ColorHex,
            'ColorText' => $ColorText,
            'Prefiks' => $Prefiks,
            'LotPlace' => $LotPlace,
            'supply' => $Supplier
        ]);

        return response()->json(['message' => 'Berhasil', 'status' => 'success']);
    }

    public function destroy($id, Request $request)
    {
        Color::where('Id', $id)->update(['status' => 'deleted']);
        
        Pallet::where('ColorId', $id)->whereNull('DateOut')->update(['ColorId' => 1]);

        if ($request->has('loc')) {
            return redirect($request->loc);
        }

        return redirect()->route('masterdata.index');
    }
}
