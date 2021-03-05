<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    public function all(): JsonResponse
    {
        return response()->json(Topic::all());
    }

    public function show(Topic $topic): JsonResponse
    {
        $topic->questionsDoc = $topic->questions()->count();
        return response()->json($topic);
    }

    public function questions(Topic $topic): JsonResponse
    {
        $questions = $topic->questions;
        foreach ($questions as $question){
            $question->user;
            $question->topics;
            $question->tags;
            $question->answersDoc = $question->answers()->count();
        }
        return response()->json($questions);
    }

    public function create(Request $request): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "name" => "required|string|min:3|max:100|unique:topics",
            "description" => "required|string|min:3|max:200",
        ]);

        if($valid->fails()) return response()->json($valid->errors());

        $topic = Topic::query()->create([
            "name" => $request->json()->get("name"),
            "description" => $request->json()->get("description")
        ]);

        return response()->json($topic);
    }

    public function update(Request $request, Topic $topic): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "name" => "required|string|min:3|max:100",
            "description" => "string|min:3|max:200",
        ]);

        if($valid->fails()) return response()->json($valid->errors());

        $topic = $topic->update([
            "name" => $request->json()->get("name"),
            "description" => $request->json()->get("description")
        ]);

        return response()->json($topic);
    }

    public function delete(Topic $topic): JsonResponse
    {
        try {
            return response()->json($topic->delete());
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
