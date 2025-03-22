<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessProposal;
use App\Models\Business;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    // Tampilkan daftar proposal usaha
    public function index()
    {
        $proposals = BusinessProposal::with('business')->get();
        return response()->json($proposals, 200);
    }

    // Lihat detail proposal
    public function show($id)
    {
        $proposal = BusinessProposal::with('business')->findOrFail($id);
        return response()->json($proposal, 200);
    }

    // Approve proposal & update bisnis
    public function approve($id)
    {
        $proposal = BusinessProposal::findOrFail($id);
        $proposal->update(['status' => 'APPROVED']);

        // Update status bisnis terkait
        $business = Business::findOrFail($proposal->business_id);
        $business->update(['status' => 'APPROVED']);

        return response()->json(['message' => 'Proposal approved successfully'], 200);
    }

    // Reject proposal & update bisnis
    public function reject($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $proposal = BusinessProposal::findOrFail($id);
        $proposal->update(['status' => 'REJECTED']);

        // Update status bisnis terkait
        $business = Business::findOrFail($proposal->business_id);
        $business->update(['status' => 'REJECTED']);

        return response()->json(['message' => 'Proposal rejected successfully'], 200);
    }
}
