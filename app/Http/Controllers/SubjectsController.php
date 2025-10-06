<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subjects::all();
        return response()->json($subjects, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only."], 403);
        }

        $validated = $request->validate([
            "name" => "required|string|max:255|unique:subjects,name",
        ]);

        $subject = Subjects::create($validated);

        return response()->json($subject, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subjects $subject) // singular parameter
    {
        return response()->json($subject, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subjects $subject)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only."], 403);
        }

        $validated = $request->validate([
            "name" => "sometimes|string|max:255|unique:subjects,name," . $subject->id,
        ]);

        $subject->update($validated);

        return response()->json($subject, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Subjects $subject)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only."], 403);
        }

        $subject->delete();
        return response()->json(null, 204);
    }
}
