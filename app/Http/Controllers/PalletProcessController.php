<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Lastinfo;
use App\Models\Color;
use App\Models\Pallet;
use App\Models\Boxbackup;

class PalletProcessController extends Controller
{
    private function newDate($lot)
    {
        $fullDate = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        Lastinfo::where('LotPlace', $lot)->update(['lastDate' => $fullDate]);
    }

    public function deleteInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            Color::where('Id', $id)->update(['status' => 'deleted']);
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function editColor(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->Id;
            Color::where('Id', $id)->update([
                'Prefiks' => $request->Prefiks,
                'InvoiceNumber' => $request->InvoiceNumber,
                'LotPlace' => $request->LotPlace,
                'supply' => $request->SupplyName,
                'ColorHex' => $request->ColorHex,
                'ColorText' => $request->ColorText,
            ]);
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function saveColor(Request $request)
    {
        DB::beginTransaction();
        try {
            Color::create([
                'Prefiks' => $request->Prefiks,
                'InvoiceNumber' => $request->InvoiceNumber,
                'LotPlace' => $request->LotPlace,
                'supply' => $request->SupplyName,
                'ColorHex' => $request->ColorHex,
                'ColorText' => $request->ColorText,
                'status' => 'active'
            ]);
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function palletAction(Request $request)
    {
        DB::beginTransaction();
        try {
            $type = $request->type;
            $IdPallet = $request->IdPallet;
            $LotNumber = $request->LotNumber;
            $BoxNumber = $request->BoxNumber;
            $ColorId = $request->ColorId;
            $PalletNumber = $request->PalletNumber;
            $ConfirmBy = session('name') ?? 'admin';
            $Date = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

            if(strlen($PalletNumber) == 1 && $PalletNumber != ''){
                $PalletNumber = "00" . $PalletNumber;
            } else if(strlen($PalletNumber) == 2 ){
                $PalletNumber = '0' . $PalletNumber;
            }

            if (in_array($type, ['save', 'edit'])) {
                if ($PalletNumber != '' && $ColorId != '1') {
                    $check = Pallet::where('PalletNumber', $PalletNumber)
                        ->where('ColorId', $ColorId)
                        ->whereNull('DateOut')
                        ->where('Id', '!=', $IdPallet)
                        ->first();
                    if ($check) {
                        DB::rollBack();
                        return response()->json(['status' => 'error', 'message' => "Maaf palet {$PalletNumber} sudah ada pada Line {$check->lineGroup}"]);
                    }
                }
            }

            $this->newDate($LotNumber);

            if ($type == 'edit') {
                Pallet::where('Id', $IdPallet)->update([
                    'LotNumber' => $LotNumber,
                    'BoxNumber' => $BoxNumber,
                    'ColorId' => $ColorId,
                    'PalletNumber' => $PalletNumber,
                    'ConfirmBy' => $ConfirmBy,
                    'DateIn' => $PalletNumber != '' ? $Date : null,
                    'errMsg' => null
                ]);
                DB::commit();
                return response()->json(['status' => 'success']);
            }

            if ($type == 'save') {
                $backup = Boxbackup::where('BoxNumber', $BoxNumber)->where('LotNumber', $LotNumber)->first();
                
                $activePalletCount = Pallet::where('BoxNumber', $BoxNumber)
                                           ->where('LotNumber', $LotNumber)
                                           ->whereNull('DateOut')
                                           ->count();

                if ($activePalletCount > 0) {
                    Pallet::where('BoxNumber', $BoxNumber)
                          ->where('LotNumber', $LotNumber)
                          ->whereNull('DateOut')
                          ->update([
                              'DateOut' => $Date,
                              'ConfirmOut' => $ConfirmBy
                          ]);
                }

                Pallet::create([
                    'LotNumber' => $LotNumber,
                    'BoxNumber' => $BoxNumber,
                    'ColorId' => $ColorId,
                    'PalletNumber' => $PalletNumber,
                    'palletGroup' => $backup ? $backup->palletGroup : 0,
                    'lineGroup' => $backup ? $backup->lineGroup : '',
                    'DateOut' => null,
                    'ConfirmBy' => $ConfirmBy,
                    'ConfirmOut' => '',
                    'DateIn' => $PalletNumber != '' ? $Date : null,
                    'errMsg' => null
                ]);

                DB::commit();
                return response()->json(['status' => 'success']);
            }

            if ($type == 'delete') {
                $pallet = Pallet::where('Id', $IdPallet)->first();
                if ($pallet) {
                    $pallet->delete();
                }
                DB::commit();
                return response()->json(['status' => 'success']);
            }

            if ($type == 'deleteLine') {
                $pallet = Pallet::where('Id', $IdPallet)->first();
                if ($pallet) {
                    Pallet::where('LotNumber', $pallet->LotNumber)
                        ->where('lineGroup', $pallet->lineGroup)
                        ->where('ColorId', '!=', 2)
                        ->whereNull('DateOut')
                        ->delete();
                }
                DB::commit();
                return response()->json(['status' => 'success']);
            }
            
            DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function prosesDataMove(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $id2 = $request->id2;
            $type = $request->type;
            $boxNumber = $request->box;
            
            $source = Pallet::where('Id', $id)->first();

            if (!$source) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pallet source not found');
            }

            $lot = $source->LotNumber;
            $this->newDate($lot);

            if ($type == 'move') {
                $target = null;
                if ($id2) {
                    $target = Pallet::where('Id', $id2)->first();
                }

                if ($target) {
                    if ($target->ColorId == 2) {
                        DB::rollBack();
                        return redirect()->route('sketch.show', ['lot' => $lot, 'id' => $id, 'type' => $type])
                            ->with('error', 'Box Ini Tidak Dapat Diisi');
                    }
                    if ($target->PalletNumber != '' && $target->PalletNumber != 'kosong') {
                        DB::rollBack();
                        return redirect()->route('sketch.show', ['lot' => $lot, 'id' => $id, 'type' => $type])
                            ->with('error', 'Box Ini Memiliki Palet');
                    }
                    $targetLineGroup = $target->lineGroup;
                    $targetPalletGroup = $target->palletGroup;
                    $target->delete();
                } else {
                    $backup = Boxbackup::where('BoxNumber', $boxNumber)->where('LotNumber', $lot)->first();
                    $targetLineGroup = $backup ? $backup->lineGroup : '';
                    $targetPalletGroup = $backup ? $backup->palletGroup : 0;
                }

                Pallet::where('Id', $id)->update([
                    'BoxNumber' => $boxNumber,
                    'lineGroup' => $targetLineGroup,
                    'palletGroup' => $targetPalletGroup
                ]);

                DB::commit();
                return redirect()->route('sketch.show', ['lot' => $lot]);
            }
            
            DB::commit();
            return redirect()->route('sketch.show', ['lot' => $lot]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
