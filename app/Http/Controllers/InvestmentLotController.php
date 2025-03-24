<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvestmentLotController extends Controller
{
    // Investasi ke proyek
    public function invest(Request $request, $businessId)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100000', // Minimal investasi 100 ribu
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $business = Business::findOrFail($businessId);
        
        if ($business->status !== 'APPROVED') {
            return response()->json(['message' => 'Proyek ini belum disetujui untuk investasi.'], 403);
        }

        DB::beginTransaction();
        try {
            // Buat transaksi investasi
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'INVESTMENT',
                'amount' => $request->amount,
            ]);

            // Tambah dana yang terkumpul di proyek
            $business->increment('fundingGoal', $request->amount);

            // Periksa apakah sudah mencapai target pendanaan
            if ($business->fundingGoal >= $business->getOriginal('fundingGoal')) {
                $business->update(['status' => 'FUNDED']);
            }
            
            DB::commit();
            return response()->json(['message' => 'Investasi berhasil!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan, coba lagi.'], 500);
        }
    }
}
