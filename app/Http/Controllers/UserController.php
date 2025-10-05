<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display all users (admin only).
     */
    public function index(Request $request)
    {
        // Optionally protect this route
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Access denied. Admins only.'], 403);
        }

        return response()->json(User::all(), 200);
    }

    /**
     * Register or create a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "first_name" => "required|string|max:255",
            "middle_name" => "nullable|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:6",
            "role" => "in:student,admin"
        ]);

        $validated['role'] = $validated['role'] ?? 'student';

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    /**
     * Display a single user.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Update a user's info.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            "first_name" => "sometimes|string|max:255",
            "middle_name" => "sometimes|nullable|string|max:255",
            "last_name" => "sometimes|string|max:255",
            "email" => "sometimes|string|email|max:255|unique:users,email," . $user->id,
            "password" => "sometimes|string|min:6",
            "role" => "sometimes|in:student,admin",
        ]);

        // Hash the password if present
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json($user, 200);
    }


    /**
     * Delete a user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
