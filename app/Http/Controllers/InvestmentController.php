<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    // Ajukan pendanaan baru
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'fundingGoal' => 'required|numeric|min:1000000', // Minimal 1 juta
            'deadline' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $entrepreneur = Auth::user()->entrepreneurDetails;

        if (!$entrepreneur) {
            return response()->json(['message' => 'Anda belum terdaftar sebagai entrepreneur'], 403);
        }

        $business = Business::create([
            'entrepreneur_id' => $entrepreneur->id, // Pakai ID dari entrepreneur_details
            'name' => $request->name,
            'description' => $request->description,
            'fundingGoal' => $request->fundingGoal,
            'deadline' => $request->deadline,
            'status' => 'PENDING', // Menunggu validasi admin
        ]);

        return response()->json(['message' => 'Pengajuan pendanaan berhasil, menunggu validasi admin', 'data' => $business], 201);
    }
}
