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
        $topics = Topics::with('subject')->get();
        return response()->json($topics, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:topics',
            'subject_id' => 'required|integer|exists:subjects,id',
        ]);

        $topics = Topics::create($validated);
        $topics->load('subject'); // 👈 load the subject relationship

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
        $topics = Topics::with('subject')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:topics,name,' . $topics->id,
            'subject_id' => 'sometimes|integer|exists:subjects,id',
        ]);

        $topics->update($validated);
        $topics->load('subject'); // 👈 reload with subject

        return response()->json($topics, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $topics = Topics::findOrFail($id);
        $topics->delete();
        return response()->json(null, 204);
    }
}
