<?php

namespace App\Http\Controllers;

use App\Models\MasterLotPlace;
use Illuminate\Http\Request;

class MasterLotPlaceController extends Controller
{
    public function index()
    {
        $lotPlaces = MasterLotPlace::orderBy('id', 'desc')->get();
        return view('masterlotplace.index', compact('lotPlaces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lot_number' => 'required',
            'alamat_gedung_utama' => 'nullable',
            'no_contact' => 'nullable',
            'nama_penanggung_jawab_perusahaan' => 'nullable',
        ]);

        MasterLotPlace::create([
            'lot_number' => $request->lot_number,
            'alamat_gedung_utama' => $request->alamat_gedung_utama,
            'no_contact' => $request->no_contact,
            'nama_penanggung_jawab_perusahaan' => $request->nama_penanggung_jawab_perusahaan,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data Lot Place berhasil ditambahkan!']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:master_lot_places,id',
            'lot_number' => 'required',
            'alamat_gedung_utama' => 'nullable',
            'no_contact' => 'nullable',
            'nama_penanggung_jawab_perusahaan' => 'nullable',
        ]);

        $item = MasterLotPlace::findOrFail($request->id);
        $item->update([
            'lot_number' => $request->lot_number,
            'alamat_gedung_utama' => $request->alamat_gedung_utama,
            'no_contact' => $request->no_contact,
            'nama_penanggung_jawab_perusahaan' => $request->nama_penanggung_jawab_perusahaan,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data Lot Place berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        $item = MasterLotPlace::findOrFail($id);
        $item->delete();

        return redirect()->route('masterlotplace.index')->with('success', 'Data Lot Place berhasil dihapus!');
    }
}
