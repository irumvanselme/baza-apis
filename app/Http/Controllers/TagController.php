<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function all(): JsonResponse
    {
        return response()->json(Tag::all());
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json($tag);
    }

    public function create(Request $request): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "name" => "required|string|min:3|max:100",
            "description" => "string|min:3|max:200",
        ]);

        if($valid->fails())
            return response()->json($valid->errors());

        $tag = Tag::query()->create([
            "name" => $request->json()->get("name"),
            "description" => $request->json()->get("description")
        ]);

        return response()->json($tag);
    }

    public function update(Request $request, Tag $tag): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            "name" => "required|string|min:3|max:100|unique:tags",
            "description" => "string|min:3|max:200",
        ]);

        if($valid->fails()) return response()->json($valid->errors());

        $tag = $tag->update([
            "name" => $request->json()->get("name"),
            "description" => $request->json()->get("description")
        ]);

        return response()->json($tag);
    }

    public function delete(Tag $tag): JsonResponse
    {
        try {
            return response()->json($tag->delete());
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
