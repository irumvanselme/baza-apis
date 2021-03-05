<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Jenssegers\Mongodb\Eloquent\Model;

class ActionController extends Controller
{
    public function has_question(Question $question, $type): JsonResponse
    {
        $action = auth()->user()->actions()
            ->where("question_id", "=", $question->_id)
            ->where("action", "=", (int)$type)->exists();
        return response()->json($action);
    }

    public function has_answer(Answer $answer, $type): JsonResponse
    {
        $action = auth()->user()->actions()
            ->where("answer_id", "=", $answer->_id)
            ->where("type", "=", $type)->exists();
        return response()->json($action);
    }

    public static function create(Model $model, $holder, $type = 1)
    {
        $opposite_type = $type === 1 ? 0 : 1;
        $action = auth()->user()->actions()
            ->where("action", "=", $type)
            ->where("holder", "=", $holder)
            ->where($holder."_id", "=", $model->_id)
            ->exists();

        $opposite = auth()->user()->actions()
            ->where("action", "=", $opposite_type)
            ->where($holder."_id", "=", $model->_id)
            ->where("holder", "=", $holder)
            ->first();

        if($action){
            try {
                return auth()->user()->actions()
                    ->where("action", "=", $type)
                    ->where("holder", "=", $holder)
                    ->where($holder."_id", "=", $model->_id)
                    ->delete();
            } catch (Exception $e) {
                return response()->json($e->getMessage());
            }
        }else {
            if($opposite) {
                return $opposite = $opposite->update([ "action" => $type ]);
            } else {
                $action = auth()->user()->actions()->create([
                    $holder."_id" => $model->_id,
                    "holder" => $holder,
                    "action" => $type
                ]);
                return $action;
            }
        }
    }

    public function delete(Action $action): JsonResponse
    {
        try {
            return response()->json($action->delete());
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
