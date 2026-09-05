<?php

namespace App\Http\Controllers;

use App\Models\MasterLotNumber;
use Illuminate\Http\Request;

class MasterLotNumberController extends Controller
{
    public function index()
    {
        $lotNumbers = MasterLotNumber::orderBy('id', 'desc')->get();
        return view('masterlotnumber.index', compact('lotNumbers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lot_place' => 'required',
            'nama_penanggung_jawab_gedung' => 'nullable',
            'no_contact' => 'nullable',
        ]);

        MasterLotNumber::create([
            'lot_place' => $request->lot_place,
            'nama_penanggung_jawab_gedung' => $request->nama_penanggung_jawab_gedung,
            'no_contact' => $request->no_contact,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data Lot Number berhasil ditambahkan!']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:master_lot_numbers,id',
            'lot_place' => 'required',
            'nama_penanggung_jawab_gedung' => 'nullable',
            'no_contact' => 'nullable',
        ]);

        $item = MasterLotNumber::findOrFail($request->id);
        $item->update([
            'lot_place' => $request->lot_place,
            'nama_penanggung_jawab_gedung' => $request->nama_penanggung_jawab_gedung,
            'no_contact' => $request->no_contact,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data Lot Number berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $item = MasterLotNumber::findOrFail($id);
        $item->delete();

        return redirect()->route('masterlotnumber.index')->with('success', 'Data Lot Number berhasil dihapus!');
    }
}
