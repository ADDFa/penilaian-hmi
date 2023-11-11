<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoulController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFoulController;
use App\Http\Controllers\UserScoreController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post("login", "login");
    Route::post("refresh", "refresh");
});

Route::middleware(Authenticate::class)->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get("user", "index");
        Route::get("user/{user}", "show");
        Route::post("user", "store");
        Route::put("user/{user}", "update");
        Route::delete("user/{user}", "destroy");
    });

    Route::controller(UserScoreController::class)->group(function () {
        Route::get("user-score", "index");
        Route::get("user-score/{userScore}", "show");
        Route::post("user-score", "store");
        Route::put("user-score/{userScore}", "update");
        Route::put("update-cognitive-score/{userScore}", "updateCognitiveScore");
        Route::delete("user-score/{userScore}", "destroy");
    });

    Route::controller(ActivityController::class)->group(function () {
        Route::patch("user-score/activity", "activityToggle");
        Route::patch("liveliness/{liveliness}", "liveliness");
    });

    Route::controller(UserFoulController::class)->group(function () {
        Route::get("user-foul", "index");
        Route::post("user-foul", "store");
        Route::put("user-foul/{userFoul}", "update");
        Route::delete("user-foul/{userFoul}", "destroy");
    });

    Route::controller(FoulController::class)->group(function () {
        Route::get("foul", "index");
    });
});
