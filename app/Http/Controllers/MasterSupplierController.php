<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supply;

class MasterSupplierController extends Controller
{
    public function index()
    {
        $lot = session('permit');
        $query = Supply::where('supplier', '!=', '');

        if ($lot == 'super') {
            $query->orderBy('LotPlace', 'ASC')->orderBy('supplier', 'ASC');
        } else if ($lot == 'GRACE') {
            $query->whereIn('LotPlace', ['GRACE', '242'])
                  ->orderBy('LotPlace', 'ASC')->orderBy('supplier', 'ASC');
        } else if ($lot == '7') {
            $query->where('LotPlace', '7')
                  ->orderBy('LotPlace', 'ASC')->orderBy('supplier', 'ASC');
        } else {
            $query->whereIn('LotPlace', ['206', 'TURUNAN206', 'REPACK'])
                  ->orderBy('LotPlace', 'ASC')->orderBy('supplier', 'ASC');
        }

        $suppliers = $query->get();
        return view('mastersupplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $supplier = $request->SupplierName;
        $LotPlace = $request->LotPlace;

        $exists = Supply::where('supplier', $supplier)
            ->where('LotPlace', $LotPlace)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Maaf data yang anda masukkan sama', 'status' => 'error']);
        }

        Supply::insert([
            'supplier' => $supplier,
            'LotPlace' => $LotPlace
        ]);

        return response()->json(['message' => 'Berhasil', 'status' => 'success']);
    }

    public function update(Request $request)
    {
        $Id = $request->Id;
        $supplier = $request->SupplierName;
        $LotPlace = $request->LotPlace;

        $exists = Supply::where('supplier', $supplier)
            ->where('LotPlace', $LotPlace)
            ->where('id', '!=', $Id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Maaf data yang anda masukkan sama', 'status' => 'error']);
        }

        Supply::where('id', $Id)->update([
            'supplier' => $supplier,
            'LotPlace' => $LotPlace
        ]);

        return response()->json(['message' => 'Berhasil', 'status' => 'success']);
    }

    public function destroy($id)
    {
        Supply::where('id', $id)->delete();
        return redirect()->route('mastersupplier.index');
    }
}
