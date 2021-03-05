<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        auth()->user()->profile;
        return response()->json(auth()->user());
    }

    public function update(Request $request): JsonResponse
    {
        $valid = Validator::make($request->json()->all(), [
            'full_name' => 'required|string|max:255|min:3',
            'username' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|min:8',
            "title" => "required|string|min:3|max:100",
            "bio" => "required|string|min:3|max:500",
            "location" => "required|string|min:3|max:255",
            "profile_pic" => "required|url|min:3|max:255",
        ]);

        if($valid->fails())
            return response()->json($valid->errors(),400);

        $user = auth()->user()->update([
            'full_name' => $request->json()->get('full_name'),
            'username' => $request->json()->get('username'),
            'email' => $request->json()->get('email')
        ]);

        $profile = auth()->user()->profile->update([
            "title" => $request->json()->get("title"),
            "bio" => $request->json()->get("bio"),
            "location" => $request->json()->get("location"),
            "profile_pic" => $request->json()->get("profile_pic"),
        ]);

        return response()->json(compact("user", "profile"));
    }
}
