<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Livelines;
use App\Models\MiddleTest;
use App\Models\UserScore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserScoreController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        function getIncrementName(string $initialName, string $value = "", int $max = 10, int $start = 1): array
        {
            $result = [];
            for ($i = $start; $i <= $max; $i++) {
                $result += [$initialName . "$i" => $value];
            }

            return $result;
        }

        $middleTestRules = getIncrementName("middle_test_", "required|integer");
        $livelinessRules = getIncrementName("liveliness_", "required|integer");

        $rules = [
            "pre_test"          => "required|integer",
            "post_test"         => "required|integer",
            "user_id"           => "required|exists:users,id|unique:user_scores,id"
        ];
        $rules += $middleTestRules;
        $rules += $livelinessRules;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return Response::errors($validator);

        return DB::transaction(function () use ($request) {
            try {
                $userScore = new UserScore([
                    "pre_test"      => $request->pre_test,
                    "post_test"     => $request->post_test,
                    "user_id"       => $request->user_id
                ]);
                $userScore->save();

                for ($i = 1; $i <= 10; $i++) {
                    $name = "middle_test_" . $i;
                    $middleTest = new MiddleTest([
                        "no_materi"     => $i,
                        "score"         => $request->$name,
                        "user_score_id" => $userScore->id
                    ]);
                    $middleTest->save();

                    $name = "liveliness_" . $i;
                    $liveliness = new Livelines([
                        "no_materi"     => $i,
                        "score"         => $request->$name,
                        "user_score_id" => $userScore->id
                    ]);
                    $liveliness->save();
                }

                DB::commit();

                $result = UserScore::with(["user", "middletest", "liveliness"])->where("user_id", $request->user_id)->first();
                return Response::success($result);
            } catch (Exception $e) {
                DB::rollBack();
                return Response::message($e->getMessage(), 500);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserScore  $userScore
     * @return \Illuminate\Http\Response
     */
    public function show(UserScore $userScore)
    {
        $result = UserScore::with(["user", "middletest", "liveliness"])->find($userScore->id);
        return Response::success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserScore  $userScore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserScore $userScore)
    {
        //
    }
}
