<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    public function all(Question $question): JsonResponse
    {
        $answers = $question->answers;
        foreach ($answers as $answer){
            $answer->user;
            $answer->likes = $answer->up_votes();
            $answer->dislikes = $answer->down_votes();
            $answer->commentsDoc = $answer->comments()->count();
        }

        return response()->json($answers);
    }

    public function all_auth(Question $question): JsonResponse
    {
        $answers = $this->all($question)->original;
        foreach ($answers as $answer){
            $answer->likes_this = (new ActionController())->has_answer($answer, 1)->original;
            $answer->dislikes_this = (new ActionController())->has_answer($answer, 0)->original;
        }
        return response()->json($answers);
    }

    public function create(Request $request, Question $question): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "body" => "required|string|min:3|max:500",
        ]);

        if($valid->fails())
            return response()->json($valid->errors(),400);

        $answer = $question->answers()->create([
            "user_id" => auth()->user()->_id,
            "body" => $request->json()->get("body")
        ]);

        $answer->user;

        return response()->json($answer, 201);
    }

    public function delete(Answer $answer): JsonResponse
    {
        try {
            return response()->json($answer->delete());
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function up_vote(Answer $answer): JsonResponse
    {
        return response()->json(ActionController::create($answer, "answer"));
    }

    public function down_vote(Answer $answer): JsonResponse
    {
        return response()->json(ActionController::create($answer, "answer", 0));
    }
}
