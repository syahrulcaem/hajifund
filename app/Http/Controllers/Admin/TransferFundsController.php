<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class TransferFundsController extends Controller
{
    // Transfer dana ke entrepreneur
    public function transfer($id)
    {
        $business = Business::findOrFail($id);

        if ($business->status !== 'FUNDED') {
            return response()->json(['message' => 'Proyek belum mencapai target dana'], 403);
        }

        DB::beginTransaction();
        try {
            // Catat transaksi transfer
            Transaction::create([
                'user_id' => $business->entrepreneur->user_id, // Kirim ke entrepreneur
                'type' => 'DEPOSIT',
                'amount' => $business->fundingGoal,
            ]);

            // Ubah status proyek menjadi COMPLETED
            $business->update(['status' => 'COMPLETED']);

            // Kirim notifikasi ke entrepreneur
            Notification::create([
                'user_id' => $business->entrepreneur->user_id,
                'title' => 'Dana Dicairkan',
                'message' => "Dana untuk proyek '{$business->name}' telah dicairkan.",
                'status' => 'UNREAD',
            ]);

            DB::commit();
            return response()->json(['message' => 'Dana berhasil ditransfer'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan, coba lagi.'], 500);
        }
    }
}
