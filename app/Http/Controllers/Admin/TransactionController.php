<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    // Tampilkan semua transaksi
    public function index()
    {
        $transactions = Transaction::with('user')->get();
        return response()->json($transactions, 200);
    }

    // Lihat detail transaksi tertentu
    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return response()->json($transaction, 200);
    }

    // Filter transaksi berdasarkan user atau jenis transaksi
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|in:DEPOSIT,WITHDRAWAL,INVESTMENT'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = Transaction::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->get();
        return response()->json($transactions, 200);
    }
}
