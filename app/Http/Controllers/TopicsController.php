<?php

namespace App\Http\Controllers;

use App\Models\Topics;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topics::all();
        return response()->json($topics, 200);
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
            'name' => 'required|string|max:255',
        ]);

        $topics = Topics::create($validated);

        return response()->json($topics, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $topic = Topics::with('subject')->findOrFail($id);
        return response()->json($topic, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only."], 403);
        }

        $topics = Topics::with('subject')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $topics->update($validated);

        return response()->json($topics, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only."], 403);
        }

        $topics = Topics::findOrFail($id);
        $topics->delete();
        return response()->json(null, 204);
    }
}
