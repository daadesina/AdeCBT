<?php

namespace App\Http\Controllers;

use App\Models\questions;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Symfony\Component\Console\Question\Question;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Questions::with(['topic', 'subject'])->get();
        return response()->json($questions, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            "content" => "required|string|max:255",
            "subject_id" => "required|exists:subjects,id",
            "topic_id" => "required|exists:topics,id",
        ]);
        $question = Questions::create($validated);
        $question->load(['topic', 'subject']);
        return response()->json($question, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(questions $question)
    {
        $question->load(['topic', 'subject']);
        return response()->json($question, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, questions $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, questions $question)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only."], 403);
        }
        $validated = $request->validate([
            "content" => "sometimes|string|max:255",
            "subject_id" => "sometimes|exists:subjects,id",
            "topic_id" => "sometimes|exists:topics,id",
        ]);
        $question->update($validated);
        $question->load(['topic', 'subject']);
        return response()->json($question, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, questions $question)
    {
        if ($request->user()->role !== 'admin'){
            return response()->json(["message"=>"Access denied. Admins only." ], 403);
        }
        $question->delete();
        return response()->json(null, 204);
    }
}
