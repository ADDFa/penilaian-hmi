<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Score;
use App\Models\User;
use App\Models\UserFoul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function show(Score $score)
    {
        $userId = $score->user_id;
        $score = Score::with(["afectiveScore", "middleTest", "liveliness"])->find($score->id);
        $user = User::find($userId);
        $fouls = UserFoul::with(["afectiveIndicator", "afectiveIndicator.category"])->where("user_id", $userId)->get();

        $result = [
            "user"  => $user,
            "score" => $score,
            "fouls" => $fouls
        ];

        return Response::success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Score  $score
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Score $score)
    {
        $validator = Validator::make($request->all(), [
            "sholat"    => "required|integer",
            "membaca_alquran"   => "required|integer",
            "ceramah_agama" => "required|integer",
            "pre_test"  => "required|integer",
            "post_test" => "required|integer",
            "penguasaan_kelompok"   => "boolean",
            "problem_solving"   => "boolean"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $data = $validator->validate();
        $data["penguasaan_kelompok"] = isset($data["penguasaan_kelompok"]) ? 10 : 0;
        $data["problem_solving"] = isset($data["problem_solving"]) ? 15 : 0;

        $score->update($data);
        return Response::success($score);
    }
}
