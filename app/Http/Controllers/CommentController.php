<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function all(Answer $answer): JsonResponse
    {
        $comments = $answer->comments;
        foreach ($comments as $comment) {
            $comment->user;
        }
        return response()->json($answer->comments);
    }

    public function create(Request $request, Answer $answer): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "body" => "required|string|min:3"
        ]);

        if($valid->fails()) return response()->json($valid->errors(), 400);

        $comment = $answer->comments()->create([
            "user_id" => auth()->user()->_id,
            "body" => $request->json()->get("body")
        ]);

        $comment->user;

        return response()->json($comment);
    }
}
