<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use App\Models\Notification;

class InvestmentValidationController extends Controller
{
    // Lihat daftar proposal yang masih pending
    public function index()
    {
        $proposals = Business::where('status', 'PENDING')->get();
        return response()->json($proposals, 200);
    }

    // Approve proposal pendanaan
    public function approve($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => 'APPROVED']);

        $entrepreneur = $business->entrepreneur; // Ambil data entrepreneur dari relasi
        $userId = $entrepreneur->user_id ?? null; // Ambil user_id dari entrepreneur_details

        if (!$userId) {
            return response()->json(['message' => 'User tidak ditemukan untuk entrepreneur ini'], 404);
        }

        // Ambil semua investor dari tabel users
        $investors = User::where('role', 'INVESTOR')->get();

        foreach ($investors as $investor) {
            Notification::create([
                'user_id' => $investor->id,
                'title' => 'Proyek Baru Tersedia!',
                'message' => "Proyek '{$business->name}' telah disetujui dan kini terbuka untuk investasi!",
                'status' => 'UNREAD',
            ]);
        }

        return response()->json(['message' => 'Proposal disetujui'], 200);
    }

    // Reject proposal pendanaan
    public function reject($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => 'REJECTED']);

        // Kirim notifikasi ke entrepreneur
        Notification::create([
            'user_id' => $business->entrepreneur_id,
            'title' => 'Proposal Ditolak',
            'message' => "Proposal '{$business->name}' ditolak oleh admin.",
            'status' => 'UNREAD',
        ]);

        return response()->json(['message' => 'Proposal ditolak'], 200);
    }
}
