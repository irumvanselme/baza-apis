<?php

use App\Http\Controllers\ActionController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\QuestionController;

// ==== Auth service

Route::group(["prefix" => "auth"], function () {
    Route::post("/register", [UserController::class, "register"]);
    Route::post("/login", [UserController::class, "login"]);
});

// ==== Profile service

Route::group(["prefix" => "profile", "middleware" => "jwt.verify"], function () {
    Route::get("", [UserController::class, "get_user"]);
    Route::get("/questions", [UserController::class, "questions"]);
    Route::put("user", [UserController::class, "update"]);
    Route::put("", [ProfileController::class, "update"]);
});

Route::group(["prefix" => "users"], function () {
    Route::get("", [UserController::class, "all"]);
    Route::get("{user}", [UserController::class, "show"]);
});

//==== Question service

Route::group(["prefix" => "questions"], function () {
    Route::get("authed", [QuestionController::class, "all_auth"])->middleware("jwt.verify");
    Route::get("/", [QuestionController::class, "all"]);
    Route::get("/trending", [QuestionController::class, "trending"]);
    Route::get("/{question}", [QuestionController::class, "show"]);
    Route::get("/{question}/answers", [AnswerController::class, "all"]);
    Route::get("/{question}/answers/authed", [AnswerController::class, "all_auth"])->middleware("jwt.verify");
    Route::group(["middleware" => "jwt.verify"], function () {
        Route::post("/", [QuestionController::class, "create"]);
        Route::post("/{question}/answers", [AnswerController::class, "create"]);
        Route::post("/{question}/like", [QuestionController::class, "up_vote"]);
        Route::post("/{question}/dislike", [QuestionController::class, "down_vote"]);
    });
});

Route::group(["prefix" => "answers"], function () {
    Route::get("/{answer}/comments", [CommentController::class, "all"]);
    Route::group(["middleware" => "jwt.verify"], function () {
        Route::post("/{answer}/comments", [CommentController::class, "create"]);
        Route::post("/{answer}/like", [AnswerController::class, "up_vote"]);
        Route::post("/{answer}/dislike", [AnswerController::class, "down_vote"]);
    });
});

Route::group(["prefix" => "topics"], function () {
    Route::get("/", [TopicController::class, "all"]);
    Route::get("/{topic}", [TopicController::class, "show"]);
    Route::get("/{topic}/questions", [TopicController::class, "questions"]);
    Route::group(["middleware" => "jwt.verify"], function () {
        Route::post("", [TopicController::class, "create"]);
        Route::put("/{topic}", [TopicController::class, "update"]);
        Route::delete("/{topic}", [TopicController::class, "delete"]);
    });
});

Route::group(["prefix" => "tags"], function () {
    Route::get("/", [TagController::class, "all"]);
    Route::get("/{tag}", [TagController::class, "show"]);
    Route::post("", [TagController::class, "create"]);
    Route::group(["middleware" => "jwt.verify"], function () {
        Route::put("/{tag}", [TagController::class, "update"]);
        Route::delete("/{tag}", [TagController::class, "delete"]);
    });
});


Route::get("/check-likes/q/{question}/{type}", [ActionController::class, "has_question"])->middleware("jwt.verify");
Route::get("/check-likes/a/{user}/{answer}/{type}", [ActionController::class, "has_answer"]);
