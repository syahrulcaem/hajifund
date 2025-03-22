<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    // Tampilkan semua laporan keuangan
    public function index()
    {
        $reports = Report::with('business')->get();
        return response()->json($reports, 200);
    }

    // Lihat detail laporan keuangan tertentu
    public function show($id)
    {
        $report = Report::with('business')->findOrFail($id);
        return response()->json($report, 200);
    }

    // Filter laporan keuangan berdasarkan bulan & tahun
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:2100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = Report::with('business');

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        $reports = $query->get();
        return response()->json($reports, 200);
    }
}
