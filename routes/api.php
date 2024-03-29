<?php

use App\Http\Controllers\AfectiveIndicatorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LivelinessController;
use App\Http\Controllers\MiddleTestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFoulController;
use App\Http\Controllers\UserTrainingController;
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
    Route::post("token", "token");
    Route::post("refresh-token", "refreshToken");
});

Route::post("register", [UserController::class, "register"]);

Route::middleware(Authenticate::class)->group(function () {
    Route::resource("user", UserController::class);

    Route::controller(TrainingController::class)->group(function () {
        Route::get("training", "index");
        Route::post("training", "store");
        Route::delete("training/{training}", "destroy");
    });

    Route::controller(UserTrainingController::class)->group(function () {
        Route::get("user-training", "index");
        Route::get("user-training/{user}", "show");
        Route::post("user-training", "store");
        Route::delete("user-training/{user}", "destroy");
    });

    Route::controller(ReportController::class)->group(function () {
        Route::get("report/{training}", "index");
        Route::put("report", "update");
    });

    Route::controller(ScoreController::class)->group(function () {
        Route::get("score/{score}", "show");
        Route::put("score/{score}", "update");
    });

    Route::controller(UserFoulController::class)->group(function () {
        Route::get("user-foul", "index");
        Route::post("user-foul", "store");
        Route::delete("user-foul/{userFoul}", "destroy");
    });

    Route::controller(AfectiveIndicatorController::class)->group(function () {
        Route::get("afective-indicators", "index");
    });

    Route::controller(MiddleTestController::class)->group(function () {
        Route::post("middle-test", "store");
        Route::put("middle-test/{middleTest}", "update");
        Route::delete("middle-test/{middleTest}", "destroy");
    });

    Route::controller(LivelinessController::class)->group(function () {
        Route::post("liveliness", "store");
        Route::put("liveliness/{liveliness}", "update");
        Route::delete("liveliness/{liveliness}", "destroy");
    });
});
