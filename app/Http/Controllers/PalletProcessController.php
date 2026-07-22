<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Lastinfo;
use App\Models\Color;
use App\Models\Pallet;
use App\Models\Boxbackup;
use App\Models\Record;
use App\Models\Recordcolor;

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
            $PalletNumber = $request->PalletNumber ?? '';
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

                $lineGroup = $backup ? $backup->lineGroup : '';
                $palletGroup = $backup ? $backup->palletGroup : 0;
                if ($LotNumber == '206') {
                    $mapFile = base_path('map-206.json');
                    if (file_exists($mapFile)) {
                        $mapData = json_decode(file_get_contents($mapFile), true);
                        if (isset($mapData[(string)$BoxNumber])) {
                            $lineGroup = $mapData[(string)$BoxNumber]['line'] ?? $lineGroup;
                            $palletGroup = $mapData[(string)$BoxNumber]['group'] ?? $palletGroup;
                        }
                    }
                }

                Pallet::create([
                    'LotNumber' => $LotNumber,
                    'BoxNumber' => $BoxNumber,
                    'ColorId' => $ColorId,
                    'PalletNumber' => $PalletNumber,
                    'palletGroup' => $palletGroup,
                    'lineGroup' => $lineGroup,
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

            if (in_array($type, ['deleteLine', 'deleteFront', 'deleteBack', 'deleteGroup'])) {
                $pallet = Pallet::where('Id', $IdPallet)->first();
                if ($pallet) {
                    $query = Pallet::where('LotNumber', $pallet->LotNumber)
                        ->where('ColorId', '!=', 2)
                        ->whereNull('DateOut');

                    if ($type == 'deleteGroup') {
                        $box = (int) $pallet->BoxNumber;
                        $lot = $pallet->LotNumber;
                        $awal = 0; $akhir = 0;
                        if ($lot == '206') {
                            $mapFile = base_path('map-206.json');
                            if (file_exists($mapFile)) {
                                $mapData = json_decode(file_get_contents($mapFile), true);
                                $mapEntry = $mapData[(string)$box] ?? null;
                                $palletGroup = $mapEntry['group'] ?? null;
                                $lineGroup = $mapEntry['line'] ?? null;
                                if ($palletGroup && $lineGroup) {
                                    $boxesInGroup = array_keys(array_filter($mapData, function($val) use ($palletGroup, $lineGroup) {
                                        return isset($val['group']) && $val['group'] == $palletGroup && 
                                               isset($val['line']) && $val['line'] == $lineGroup;
                                    }));
                                    if (!empty($boxesInGroup)) {
                                        $awal = min($boxesInGroup);
                                        $akhir = max($boxesInGroup);
                                    }
                                }
                            }
                        }
                        if ($awal == 0 || $akhir == 0) {
                            if ($box >= 196 && $lot == '7') {
                                $max = 2;
                                $awal = ($box % $max == 0) ? (intdiv($box, $max) * $max - $max + 2) : (intdiv($box, $max) * $max);
                            } else {
                                $max = 3;
                                $awal = ($box % $max == 0) ? (intdiv($box, $max) * $max - $max + 1) : (intdiv($box, $max) * $max + 1);
                            }
                            if ($lot == '242') {
                                $max = 2;
                                $awal = ($box % $max != 0) ? (intdiv($box, $max) * $max + 1) : (intdiv($box, $max) * $max - $max + 1);
                            }
                            $akhir = $awal + $max - 1;
                        }
                        $query->whereBetween('BoxNumber', [$awal, $akhir]);
                    } else {
                        if ($pallet->LotNumber == '206') {
                            $mapFile = base_path('map-206.json');
                            if (file_exists($mapFile)) {
                                $mapData = json_decode(file_get_contents($mapFile), true);
                                $mapEntry = $mapData[(string)$pallet->BoxNumber] ?? null;
                                $lineGroup = $mapEntry['line'] ?? null;
                                if ($lineGroup) {
                                    $boxesInLine = array_keys(array_filter($mapData, function($val) use ($lineGroup) {
                                        return isset($val['line']) && $val['line'] == $lineGroup;
                                    }));
                                    $query->whereIn('BoxNumber', $boxesInLine);
                                } else {
                                    $query->where('lineGroup', $pallet->lineGroup);
                                }
                            } else {
                                $query->where('lineGroup', $pallet->lineGroup);
                            }
                        } else {
                            if ($pallet->lineGroup == '') {
                                DB::rollBack();
                                return response()->json(['status' => 'error', 'message' => 'Line group kosong']);
                            }
                            $query->where('lineGroup', $pallet->lineGroup);
                        }

                        if ($type == 'deleteFront') {
                            $query->where('BoxNumber', '>=', $pallet->BoxNumber);
                        } elseif ($type == 'deleteBack') {
                            $query->where('BoxNumber', '<=', $pallet->BoxNumber);
                        }
                    }

                    $query->delete();
                }
                DB::commit();
                return response()->json(['status' => 'success']);
            }
            if ($type == 'return') {
                $pallet = Pallet::where('Id', $IdPallet)->first();
                if ($pallet && $pallet->PalletNumber != '') {
                    $pallet->update([
                        'DateOut' => $Date,
                        'ConfirmOut' => $ConfirmBy
                    ]);
                    Pallet::create([
                        'LotNumber' => $pallet->LotNumber,
                        'BoxNumber' => $pallet->BoxNumber,
                        'ColorId' => $pallet->ColorId,
                        'PalletNumber' => '',
                        'palletGroup' => $pallet->palletGroup,
                        'lineGroup' => $pallet->lineGroup,
                        'ConfirmBy' => '',
                        'ConfirmOut' => '',
                        'DateOut' => null
                    ]);
                }
                DB::commit();
                return response()->json(['status' => 'success']);
            }

            if (in_array($type, ['returnGroup', 'returnLine', 'returnFront', 'returnBack'])) {
                $pallet = Pallet::where('Id', $IdPallet)->first();
                if ($pallet) {
                    $query = Pallet::where('LotNumber', $pallet->LotNumber)
                        ->whereNull('DateOut');

                    if ($type == 'returnGroup') {
                        $box = (int) $pallet->BoxNumber;
                        $lot = $pallet->LotNumber;
                        $awal = 0; $akhir = 0;
                        if ($lot == '206') {
                            $mapFile = base_path('map-206.json');
                            if (file_exists($mapFile)) {
                                $mapData = json_decode(file_get_contents($mapFile), true);
                                $mapEntry = $mapData[(string)$box] ?? null;
                                $palletGroup = $mapEntry['group'] ?? null;
                                $lineGroup = $mapEntry['line'] ?? null;
                                if ($palletGroup && $lineGroup) {
                                    $boxesInGroup = array_keys(array_filter($mapData, function($val) use ($palletGroup, $lineGroup) {
                                        return isset($val['group']) && $val['group'] == $palletGroup && 
                                               isset($val['line']) && $val['line'] == $lineGroup;
                                    }));
                                    if (!empty($boxesInGroup)) {
                                        $awal = min($boxesInGroup);
                                        $akhir = max($boxesInGroup);
                                    }
                                }
                            }
                        }
                        if ($awal == 0 || $akhir == 0) {
                            if ($box >= 196 && $lot == '7') {
                                $max = 2;
                                $awal = ($box % $max == 0) ? (intdiv($box, $max) * $max - $max + 2) : (intdiv($box, $max) * $max);
                            } else {
                                $max = 3;
                                $awal = ($box % $max == 0) ? (intdiv($box, $max) * $max - $max + 1) : (intdiv($box, $max) * $max + 1);
                            }
                            if ($lot == '242') {
                                $max = 2;
                                $awal = ($box % $max != 0) ? (intdiv($box, $max) * $max + 1) : (intdiv($box, $max) * $max - $max + 1);
                            }
                            $akhir = $awal + $max - 1;
                        }
                        $query->whereBetween('BoxNumber', [$awal, $akhir]);
                    } else {
                        if ($pallet->LotNumber == '206') {
                            $mapFile = base_path('map-206.json');
                            if (file_exists($mapFile)) {
                                $mapData = json_decode(file_get_contents($mapFile), true);
                                $mapEntry = $mapData[(string)$pallet->BoxNumber] ?? null;
                                $lineGroup = $mapEntry['line'] ?? null;
                                if ($lineGroup) {
                                    $boxesInLine = array_keys(array_filter($mapData, function($val) use ($lineGroup) {
                                        return isset($val['line']) && $val['line'] == $lineGroup;
                                    }));
                                    $query->whereIn('BoxNumber', $boxesInLine);
                                } else {
                                    $query->where('lineGroup', $pallet->lineGroup);
                                }
                            } else {
                                $query->where('lineGroup', $pallet->lineGroup);
                            }
                        } else {
                            if ($pallet->lineGroup == '') {
                                DB::rollBack();
                                return response()->json(['status' => 'error', 'message' => 'Line group kosong']);
                            }
                            $query->where('lineGroup', $pallet->lineGroup);
                        }
                        
                        if ($type == 'returnFront') {
                            $query->where('BoxNumber', '>=', $pallet->BoxNumber);
                        } elseif ($type == 'returnBack') {
                            $query->where('BoxNumber', '<=', $pallet->BoxNumber);
                        }
                    }
                    
                    $palletsInLine = $query->get();
                    foreach ($palletsInLine as $p) {
                        if ($p->ColorId == 2) continue;
                        if ($p->PalletNumber != '') {
                            $p->update(['DateOut' => $Date, 'ConfirmOut' => $ConfirmBy]);
                            Pallet::create([
                                'LotNumber' => $p->LotNumber,
                                'BoxNumber' => $p->BoxNumber,
                                'ColorId' => 1,
                                'PalletNumber' => '',
                                'palletGroup' => $p->palletGroup,
                                'lineGroup' => $p->lineGroup,
                                'ConfirmBy' => '',
                                'ConfirmOut' => '',
                                'DateOut' => null
                            ]);
                        } else {
                            $p->update(['ColorId' => 1]);
                        }
                    }
                }
                DB::commit();
                return response()->json(['status' => 'success']);
            }

            if (in_array($type, ['groupcolor', 'groupFront', 'groupBack', 'color', 'colorFront', 'colorBack'])) {
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

                $pallet = Pallet::where('Id', $IdPallet)->first();
                if (!$pallet && $LotNumber && $BoxNumber) {
                    $pallet = Pallet::where('LotNumber', $LotNumber)
                        ->where('BoxNumber', $BoxNumber)
                        ->whereNull('DateOut')
                        ->first();
                }
                
                if (!$pallet && $LotNumber && $BoxNumber) {
                    $pGroup = 0; $lGroup = '';
                    if ($LotNumber == '206') {
                        $mapFile = base_path('map-206.json');
                        if (file_exists($mapFile)) {
                            $mapData = json_decode(file_get_contents($mapFile), true);
                            $mapEntry = $mapData[(string)$BoxNumber] ?? null;
                            if ($mapEntry) {
                                $pGroup = $mapEntry['group'] ?? 0;
                                $lGroup = $mapEntry['line'] ?? '';
                            }
                        }
                    } else {
                        $backup = Boxbackup::where('LotNumber', $LotNumber)->where('BoxNumber', $BoxNumber)->first();
                        if ($backup) {
                            $pGroup = $backup->palletGroup;
                            $lGroup = $backup->lineGroup;
                        }
                    }
                    
                    $pallet = Pallet::create([
                        'LotNumber' => $LotNumber,
                        'BoxNumber' => $BoxNumber,
                        'ColorId' => $ColorId,
                        'PalletNumber' => $PalletNumber,
                        'palletGroup' => $pGroup,
                        'lineGroup' => $lGroup,
                        'DateOut' => null,
                        'ConfirmBy' => $ConfirmBy,
                        'ConfirmOut' => '',
                        'DateIn' => ($PalletNumber != '') ? $Date : null,
                        'errMsg' => null
                    ]);
                }

                if ($pallet) {
                    // Update the current pallet's properties before applying ColorId to the group
                    $pallet->update([
                        'ColorId' => $ColorId,
                        'PalletNumber' => $PalletNumber,
                        'ConfirmBy' => $ConfirmBy,
                        'DateIn' => ($PalletNumber != '' && $pallet->PalletNumber == '') ? $Date : $pallet->DateIn,
                        'errMsg' => null
                    ]);

                    $targetBoxes = [];
                    if (in_array($type, ['groupcolor', 'groupFront', 'groupBack'])) {
                        $box = (int) $pallet->BoxNumber;
                        $lot = $pallet->LotNumber;
                        $awal = 0; $akhir = 0;
                        if ($lot == '206') {
                            $mapFile = base_path('map-206.json');
                            if (file_exists($mapFile)) {
                                $mapData = json_decode(file_get_contents($mapFile), true);
                                $mapEntry = $mapData[(string)$box] ?? null;
                                $palletGroup = $mapEntry['group'] ?? null;
                                $lineGroup = $mapEntry['line'] ?? null;
                                if ($palletGroup && $lineGroup) {
                                    $boxesInGroup = array_keys(array_filter($mapData, function($val) use ($palletGroup, $lineGroup) {
                                        return isset($val['group']) && $val['group'] == $palletGroup && 
                                               isset($val['line']) && $val['line'] == $lineGroup;
                                    }));
                                    if (!empty($boxesInGroup)) {
                                        $awal = min($boxesInGroup);
                                        $akhir = max($boxesInGroup);
                                    }
                                }
                            }
                        }
                        if ($awal == 0 || $akhir == 0) {
                            if ($box >= 196 && $lot == '7') {
                                $max = 2;
                                $awal = ($box % $max == 0) ? (intdiv($box, $max) * $max - $max + 2) : (intdiv($box, $max) * $max);
                            } else {
                                $max = 3;
                                $awal = ($box % $max == 0) ? (intdiv($box, $max) * $max - $max + 1) : (intdiv($box, $max) * $max + 1);
                            }
                            if ($lot == '242') {
                                $max = 2;
                                $awal = ($box % $max != 0) ? (intdiv($box, $max) * $max + 1) : (intdiv($box, $max) * $max - $max + 1);
                            }
                            $akhir = $awal + $max - 1;
                        }
                        
                        if ($type == 'groupFront') {
                            $targetBoxes = range($pallet->BoxNumber, $akhir);
                        } elseif ($type == 'groupBack') {
                            $targetBoxes = range($awal, $pallet->BoxNumber);
                        } else {
                            $targetBoxes = range($awal, $akhir);
                        }
                    } else {
                        if ($pallet->LotNumber == '206') {
                            $mapFile = base_path('map-206.json');
                            if (file_exists($mapFile)) {
                                $mapData = json_decode(file_get_contents($mapFile), true);
                                $mapEntry = $mapData[(string)$pallet->BoxNumber] ?? null;
                                $lineGroup = $mapEntry['line'] ?? null;
                                if ($lineGroup) {
                                    $boxesInLine = array_keys(array_filter($mapData, function($val) use ($lineGroup) {
                                        return isset($val['line']) && $val['line'] == $lineGroup;
                                    }));
                                    
                                    if ($type == 'colorFront') {
                                        $targetBoxes = array_filter($boxesInLine, fn($b) => $b >= $pallet->BoxNumber);
                                    } elseif ($type == 'colorBack') {
                                        $targetBoxes = array_filter($boxesInLine, fn($b) => $b <= $pallet->BoxNumber);
                                    } else {
                                        $targetBoxes = $boxesInLine;
                                    }
                                }
                            }
                        } else {
                            if ($pallet->lineGroup == '') {
                                DB::rollBack();
                                return response()->json(['status' => 'error', 'message' => 'Line group kosong']);
                            }
                            $backupQuery = Boxbackup::where('LotNumber', $pallet->LotNumber)->where('lineGroup', $pallet->lineGroup);
                            if ($type == 'colorFront') {
                                $backupQuery->where('BoxNumber', '>=', $pallet->BoxNumber);
                            } elseif ($type == 'colorBack') {
                                $backupQuery->where('BoxNumber', '<=', $pallet->BoxNumber);
                            }
                            $targetBoxes = $backupQuery->pluck('BoxNumber')->toArray();
                        }
                    }

                    $filledBox = Pallet::where('LotNumber', $pallet->LotNumber)
                                       ->whereIn('BoxNumber', $targetBoxes)
                                       ->where('Id', '!=', $pallet->Id)
                                       ->where('PalletNumber', '!=', '')
                                       ->whereNull('DateOut')
                                       ->first();
                    if ($filledBox) {
                        DB::rollBack();
                        return response()->json([
                            'status' => 'error', 
                            'message' => 'Gagal! Ada box di dalam Group/Line ini (misal Box ' . $filledBox->BoxNumber . ') yang sudah terisi Pallet No.'
                        ]);
                    }

                    foreach ($targetBoxes as $b) {
                        $existing = Pallet::where('LotNumber', $pallet->LotNumber)
                            ->where('BoxNumber', $b)
                            ->whereNull('DateOut')
                            ->first();
                        
                        if ($existing) {
                            if ($existing->ColorId != 2) {
                                $existing->update(['ColorId' => $ColorId]);
                            }
                        } else {
                            $pGroup = 0;
                            $lGroup = '';
                            $shouldCreate = false;

                            if ($pallet->LotNumber == '206') {
                                $mapFile = base_path('map-206.json');
                                if (file_exists($mapFile)) {
                                    $mapData = json_decode(file_get_contents($mapFile), true);
                                    $mapEntry = $mapData[(string)$b] ?? null;
                                    if ($mapEntry) {
                                        $pGroup = $mapEntry['group'] ?? 0;
                                        $lGroup = $mapEntry['line'] ?? '';
                                        $shouldCreate = true;
                                    }
                                }
                            } else {
                                $backup = Boxbackup::where('LotNumber', $pallet->LotNumber)
                                    ->where('BoxNumber', $b)
                                    ->first();
                                if ($backup) {
                                    $pGroup = $backup->palletGroup;
                                    $lGroup = $backup->lineGroup;
                                    $shouldCreate = true;
                                }
                            }

                            if ($shouldCreate) {
                                Pallet::create([
                                    'LotNumber' => $pallet->LotNumber,
                                    'BoxNumber' => $b,
                                    'ColorId' => $ColorId,
                                    'PalletNumber' => '',
                                    'palletGroup' => $pGroup,
                                    'lineGroup' => $lGroup,
                                    'DateOut' => null,
                                    'ConfirmBy' => $ConfirmBy,
                                    'ConfirmOut' => '',
                                    'DateIn' => null,
                                    'errMsg' => null
                                ]);
                            }
                        }
                    }
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

            if (in_array($type, ['move', 'moveGroup', 'moveLine'])) {
                $tgtBox = (int) $boxNumber;
                $srcBox = (int) $source->BoxNumber;
                
                $srcBoxes = [];
                $tgtBoxes = [];

                if ($type == 'move') {
                    $srcBoxes = [$srcBox];
                    $tgtBoxes = [$tgtBox];
                } else {
                    $srcMapData = [];
                    if ($lot == '206' && file_exists(base_path('map-206.json'))) {
                        $srcMapData = json_decode(file_get_contents(base_path('map-206.json')), true);
                    }
                    
                    if ($type == 'moveGroup') {
                        $awal = 0; $akhir = 0;
                        if ($lot == '206' && $srcMapData) {
                            $palletGroup = $srcMapData[(string)$srcBox]['group'] ?? null;
                            $lineGroup = $srcMapData[(string)$srcBox]['line'] ?? null;
                            if ($palletGroup && $lineGroup) {
                                $boxesInGroup = array_keys(array_filter($srcMapData, function($val) use ($palletGroup, $lineGroup) {
                                    return isset($val['group']) && $val['group'] == $palletGroup && 
                                           isset($val['line']) && $val['line'] == $lineGroup;
                                }));
                                if (!empty($boxesInGroup)) {
                                    $awal = min($boxesInGroup);
                                    $akhir = max($boxesInGroup);
                                }
                            }
                        }
                        if ($awal == 0 || $akhir == 0) {
                            if ($srcBox >= 196 && $lot == '7') {
                                $max = 2;
                                $awal = ($srcBox % $max == 0) ? (intdiv($srcBox, $max) * $max - $max + 2) : (intdiv($srcBox, $max) * $max);
                            } else {
                                $max = 3;
                                $awal = ($srcBox % $max == 0) ? (intdiv($srcBox, $max) * $max - $max + 1) : (intdiv($srcBox, $max) * $max + 1);
                            }
                            if ($lot == '242') {
                                $max = 2;
                                $awal = ($srcBox % $max != 0) ? (intdiv($srcBox, $max) * $max + 1) : (intdiv($srcBox, $max) * $max - $max + 1);
                            }
                            $akhir = $awal + $max - 1;
                        }
                        $srcBoxes = range($awal, $akhir);
                        
                        $awal = 0; $akhir = 0;
                        if ($lot == '206' && $srcMapData) {
                            $palletGroup = $srcMapData[(string)$tgtBox]['group'] ?? null;
                            $lineGroup = $srcMapData[(string)$tgtBox]['line'] ?? null;
                            if ($palletGroup && $lineGroup) {
                                $boxesInGroup = array_keys(array_filter($srcMapData, function($val) use ($palletGroup, $lineGroup) {
                                    return isset($val['group']) && $val['group'] == $palletGroup && 
                                           isset($val['line']) && $val['line'] == $lineGroup;
                                }));
                                if (!empty($boxesInGroup)) {
                                    $awal = min($boxesInGroup);
                                    $akhir = max($boxesInGroup);
                                }
                            }
                        }
                        if ($awal == 0 || $akhir == 0) {
                            if ($tgtBox >= 196 && $lot == '7') {
                                $max = 2;
                                $awal = ($tgtBox % $max == 0) ? (intdiv($tgtBox, $max) * $max - $max + 2) : (intdiv($tgtBox, $max) * $max);
                            } else {
                                $max = 3;
                                $awal = ($tgtBox % $max == 0) ? (intdiv($tgtBox, $max) * $max - $max + 1) : (intdiv($tgtBox, $max) * $max + 1);
                            }
                            if ($lot == '242') {
                                $max = 2;
                                $awal = ($tgtBox % $max != 0) ? (intdiv($tgtBox, $max) * $max + 1) : (intdiv($tgtBox, $max) * $max - $max + 1);
                            }
                            $akhir = $awal + $max - 1;
                        }
                        $tgtBoxes = range($awal, $akhir);
                    } else if ($type == 'moveLine') {
                        if ($lot == '206' && $srcMapData) {
                            $lineGroup = $srcMapData[(string)$srcBox]['line'] ?? null;
                            if ($lineGroup) {
                                $srcBoxes = array_keys(array_filter($srcMapData, function($val) use ($lineGroup) {
                                    return isset($val['line']) && $val['line'] == $lineGroup;
                                }));
                            }
                            $lineGroupTgt = $srcMapData[(string)$tgtBox]['line'] ?? null;
                            if ($lineGroupTgt) {
                                $tgtBoxes = array_keys(array_filter($srcMapData, function($val) use ($lineGroupTgt) {
                                    return isset($val['line']) && $val['line'] == $lineGroupTgt;
                                }));
                            }
                        } else {
                            if ($source->lineGroup != '') {
                                $srcBoxes = Boxbackup::where('LotNumber', $lot)->where('lineGroup', $source->lineGroup)->pluck('BoxNumber')->toArray();
                            }
                            $tgtBackup = Boxbackup::where('LotNumber', $lot)->where('BoxNumber', $tgtBox)->first();
                            if ($tgtBackup && $tgtBackup->lineGroup != '') {
                                $tgtBoxes = Boxbackup::where('LotNumber', $lot)->where('lineGroup', $tgtBackup->lineGroup)->pluck('BoxNumber')->toArray();
                            }
                        }
                    }
                }
                
                sort($srcBoxes, SORT_NUMERIC);
                sort($tgtBoxes, SORT_NUMERIC);
                
                $sourcePallets = Pallet::where('LotNumber', $lot)
                                       ->whereIn('BoxNumber', $srcBoxes)
                                       ->whereNull('DateOut')
                                       ->get();

                foreach ($sourcePallets as $p) {
                    $idx = array_search($p->BoxNumber, $srcBoxes);
                    if ($idx !== false && isset($tgtBoxes[$idx])) {
                        $tBox = $tgtBoxes[$idx];
                        $targetPallet = Pallet::where('LotNumber', $lot)->where('BoxNumber', $tBox)->whereNull('DateOut')->first();
                        if ($targetPallet && !in_array($targetPallet->BoxNumber, $srcBoxes)) {
                            if ($targetPallet->ColorId == 2) {
                                DB::rollBack();
                                return redirect()->route('sketch.show', ['lot' => $lot, 'id' => $id, 'type' => $type])
                                    ->with('error', "Box $tBox Tidak Dapat Diisi");
                            }
                            if ($targetPallet->PalletNumber != '' && $targetPallet->PalletNumber != 'kosong') {
                                DB::rollBack();
                                return redirect()->route('sketch.show', ['lot' => $lot, 'id' => $id, 'type' => $type])
                                    ->with('error', "Box $tBox Memiliki Palet");
                            }
                        }
                    }
                }

                foreach ($sourcePallets as $p) {
                    $idx = array_search($p->BoxNumber, $srcBoxes);
                    if ($idx !== false && isset($tgtBoxes[$idx])) {
                        $tBox = $tgtBoxes[$idx];
                        Pallet::where('LotNumber', $lot)->where('BoxNumber', $tBox)->whereNotIn('BoxNumber', $srcBoxes)->whereNull('DateOut')->delete();
                    }
                }

                $mapDataAll = null;
                if ($lot == '206' && file_exists(base_path('map-206.json'))) {
                    $mapDataAll = json_decode(file_get_contents(base_path('map-206.json')), true);
                }

                foreach ($sourcePallets as $p) {
                    $idx = array_search($p->BoxNumber, $srcBoxes);
                    if ($idx !== false && isset($tgtBoxes[$idx])) {
                        $tBox = $tgtBoxes[$idx];
                        $pGroup = 0;
                        $lGroup = '';
                        if ($lot == '206' && $mapDataAll) {
                            $pGroup = $mapDataAll[(string)$tBox]['group'] ?? 0;
                            $lGroup = $mapDataAll[(string)$tBox]['line'] ?? '';
                        } else {
                            $backup = Boxbackup::where('LotNumber', $lot)->where('BoxNumber', $tBox)->first();
                            $pGroup = $backup ? $backup->palletGroup : 0;
                            $lGroup = $backup ? $backup->lineGroup : '';
                        }
                        $p->update(['BoxNumber' => -$tBox, 'lineGroup' => $lGroup, 'palletGroup' => $pGroup]);
                    }
                }

                foreach ($sourcePallets as $p) {
                    if ($p->BoxNumber < 0) {
                        $p->update(['BoxNumber' => -$p->BoxNumber]);
                    }
                }

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

    public function recordData(Request $request)
    {
        DB::beginTransaction();
        try {
            $lot = $request->LotNumber;
            if (!$lot) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Lot Number is required']);
            }

            $lot2 = $lot;
            if (in_array($lot, ['243', '244', '245'])) {
                $lot2 = 'GRACE';
            }
            
            $Date = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $shortDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');

            // Delete existing recordcolors for today
            Recordcolor::where('LotPlace', $lot2)
                ->where('recordDate', $shortDate)
                ->delete();

            // Fetch colors that have active pallets and insert into recordcolors
            $colorsWithPallets = Color::where('LotPlace', $lot2)
                ->whereRaw("(SELECT COUNT(id) FROM pallets WHERE pallets.ColorId = colors.Id AND pallets.DateOut IS NULL AND pallets.PalletNumber != '') != 0")
                ->get();

            foreach ($colorsWithPallets as $color) {
                Recordcolor::create([
                    'LotPlace' => $color->LotPlace,
                    'Prefiks' => $color->Prefiks,
                    'ColorId' => $color->Id,
                    'InvoiceNumber' => $color->InvoiceNumber,
                    'ColorHex' => $color->ColorHex,
                    'ColorText' => $color->ColorText,
                    'supply' => $color->supply,
                    'recordDate' => $Date
                ]);
            }

            // Delete existing records for today
            Record::where('LotNumber', $lot)
                ->where('Date', $shortDate)
                ->delete();

            // Fetch active pallets and insert into records
            $activePallets = Pallet::where('LotNumber', $lot)
                ->whereNull('DateOut')
                ->where(function ($query) {
                    $query->where('PalletNumber', '!=', '')
                          ->orWhere('ColorId', 2);
                })
                ->get();

            foreach ($activePallets as $pallet) {
                Record::create([
                    'LotNumber' => $pallet->LotNumber,
                    'BoxNumber' => $pallet->BoxNumber,
                    'ColorId' => $pallet->ColorId,
                    'PalletNumber' => $pallet->PalletNumber,
                    'Date' => $shortDate,
                    'exactDate' => $Date
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil di Record!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
