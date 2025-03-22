<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\EntrepreneurDetails;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'INVESTOR' // Default role investor
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // CEK USER (ME)
    public function me()
    {
        return response()->json(Auth::user());
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    // BERGABUNG SEBAGAI INVESTOR (Upload KTP)
    public function joinInvestor(Request $request)
    {
        $user = Auth::user();

        if ($user->userDetails) {
            return response()->json(['message' => 'You have already submitted your details'], 400);
        }

        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string',
            'phone' => 'required|string',
            'ktpNumber' => 'required|string|unique:user_details,ktpNumber',
            'bankAccount' => 'required|string',
            'ktpImage' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Simpan foto KTP
        $ktpPath = $request->file('ktpImage')->store('ktp_images', 'public');

        // Simpan data user details
        UserDetails::create([
            'user_id' => $user->id,
            'fullName' => $request->fullName,
            'phone' => $request->phone,
            'ktpNumber' => $request->ktpNumber,
            'bankAccount' => $request->bankAccount,
            'is_approved' => false, // Harus di-ACC admin dulu
            'ktpImage' => $ktpPath
        ]);

        return response()->json(['message' => 'Investor details submitted, waiting for admin approval'], 201);
    }

    // BERGABUNG SEBAGAI ENTREPRENEUR (Upload Dokumen Usaha)
    public function joinEntrepreneur(Request $request)
    {
        $user = Auth::user();

        if ($user->entrepreneurDetails) {
            return response()->json(['message' => 'You have already submitted your entrepreneur details'], 400);
        }

        $validator = Validator::make($request->all(), [
            'npwp' => 'required|string|unique:entrepreneur_details,npwp',
            'portfolio' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Simpan foto dokumen usaha
        $portfolioPath = $request->file('portfolio')->store('entrepreneur_portfolios', 'public');

        // Simpan data entrepreneur details
        EntrepreneurDetails::create([
            'user_id' => $user->id,
            'npwp' => $request->npwp,
            'portfolio' => $portfolioPath
        ]);

        return response()->json(['message' => 'Entrepreneur details submitted, waiting for admin approval'], 201);
    }

    // Generate response token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
