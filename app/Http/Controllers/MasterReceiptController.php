<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Receipt;

class MasterReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::orderBy('id', 'ASC')->get();
        return view('masterreceipt.index', compact('receipts'));
    }

    public function store(Request $request)
    {
        $data = $request->receiptData;
        
        // Delete all old receipts
        Receipt::query()->delete();

        $splitted = explode("\n", $data);
        $dateAdd = now()->format('Y-m-d H:i:s');
        $loop = 1;

        // Using transaction for bulk insert
        DB::beginTransaction();
        try {
            $insertData = [];
            for ($i = 0; $i < count($splitted) - 1; $i++) {
                $split2 = explode("\t", $splitted[$i]); // Assuming tab separated
                
                if (count($split2) >= 2) {
                    $invoiceNumber = trim($split2[0]);
                    $palletNumber = trim($split2[1]);
                    
                    // Check duplicate in array to avoid multiple inserts
                    $exists = collect($insertData)->where('invoiceNumber', $invoiceNumber)
                                                  ->where('palletNumber', $palletNumber)
                                                  ->count();
                                                  
                    if ($exists == 0) {
                        $insertData[] = [
                            'id' => $loop,
                            'invoiceNumber' => $invoiceNumber,
                            'palletNumber' => $palletNumber,
                            'dateAdd' => $dateAdd
                        ];
                        $loop++;
                    }
                }
            }
            
            // Insert all at once
            foreach (array_chunk($insertData, 500) as $chunk) {
                Receipt::insert($chunk);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing receipt data: ' . $e->getMessage());
        }

        return redirect()->route('masterreceipt.index')->with('success', 'Berhasil update data receipt');
    }

    public function destroy(Request $request)
    {
        // To handle the different GET ?delete=all or ?id= parameter if we want, but sticking to standard structure:
        // Actually, the original allowed deleting by id, invoice, date, or all.
        // Let's implement destroy method for id
        if ($request->has('delete') && $request->delete == 'all') {
            Receipt::query()->delete();
        } elseif ($request->has('invoice')) {
            Receipt::where('invoiceNumber', $request->invoice)->delete();
        } elseif ($request->has('date')) {
            Receipt::where('dateAdd', $request->date)->delete();
        } elseif ($request->has('id')) {
            Receipt::where('id', $request->id)->delete();
        }

        return redirect()->route('masterreceipt.index');
    }
}
