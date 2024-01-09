<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
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
});
