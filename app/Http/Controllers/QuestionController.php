<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function all(): JsonResponse
    {
        $questions = Question::query()->orderByDesc("created_at")->get();
        foreach ($questions as $question){
            $question->user;
            $question->topics;
            $question->tags;
            $question->likes = $question->up_votes();
            $question->dislikes = $question->down_votes();
            $question->answersDoc = $question->answers()->count();
        }
        return response()->json($questions);
    }

    public function all_auth(): JsonResponse
    {
        $questions = $this->all()->original;
        foreach ($questions as $question){
            $question->likes_this = (new ActionController())->has_question($question, 1)->original;
            $question->dislikes_this = (new ActionController())->has_question($question, 0)->original;
        }
        return response()->json($questions);
    }

    public function trending(): JsonResponse
    {
        $questions = Question::query()->limit(7)->get();
        return response()->json($questions);
    }

    public function show(Question $question): JsonResponse
    {
        $question->user;
        $question->topics;
        $question->tags;
        $question->likes = $question->up_votes();
        $question->dislikes = $question->down_votes();
        $question->answersDoc = $question->answers->count();
        return response()->json($question);
    }

    public function create(Request $request): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "title" => "required|string|min:3|max:200",
            "body" => "string|max:500",
            "topics" => "required|array|max:5",
            "tags" => "array"
        ]);

        if($valid->fails()) return response()->json($valid->errors(), 400);

        $topics = [];
        foreach ($request->json()->get("topics") as $topic) $topics[] = Topic::query()->findOrFail($topic);
        if ($tags = $request->json()->get("tags"))
            foreach ($request->json()->get("tags") as $tag)
                $tags[] = Topic::query()->findOrNew($tag, ["name" => $tag]);

        if($valid->fails())
            return response()->json($valid->errors(),400);

        $question = auth()->user()->questions()->create([
            "title" => $request->json()->get("title"),
            "body" => $request->json()->get("body")
        ]);

        foreach ($topics as $topic) $question->topics()->attach($topic);
        if($tags) foreach ($tags as $tag) $question->tags()->attach($tag);

        $question->user;
        $question->topics;

        return response()->json($question, 201);
    }

    public function up_vote(Question $question): JsonResponse
    {
        return response()->json(ActionController::create($question, "question"));
    }

    public function down_vote(Question $question): JsonResponse
    {
        return response()->json(ActionController::create($question, "question", 0));
    }
}
