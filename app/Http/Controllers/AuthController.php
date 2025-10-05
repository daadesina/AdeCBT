<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function login(Request $request){
        $validated = $request->validate([
            "email"=>"required|string|max:256|email",
            "password"=> "required|string|max:255"
        ]);
        $user = User::where("email", $validated["email"])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)){
            return response()->json(['message'=> 'Invalid credentials'], 401);
        }

        // Create token (required Sanctum)
        $token = $user -> createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
{
    try {
        $user = $request->user();

        // If user is not authenticated (no valid Sanctum token)
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized. No valid token provided.'
            ], 401);
        }

        // Get the current access token (may be null if expired or invalid)
        $token = $user->currentAccessToken();

        if ($token && $token instanceof PersonalAccessToken) {
            $token->delete();

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        }

        // Token not found or invalid
        return response()->json([
            'message' => 'Invalid or expired token.'
        ], 401);

    } catch (\Throwable $e) {
        // Catch all exceptions and return clean JSON error
        return response()->json([
            'message' => 'Server error during logout.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
