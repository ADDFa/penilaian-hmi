<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Response;
use App\Models\Livelines;
use App\Models\MiddleTest;
use App\Models\UserFoul;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "take"      => "integer",
            "before"    => "integer|exists:user_scores,id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $take = $request->take ?? 100;

        $userScores = UserScore::with(["user", "middletest", "liveliness"])->take($take)->orderBy("id", "desc");
        if ($request->before) {
            $userScores = $userScores->where("id", "<=", $request->before);
        }
        $userScores = $userScores->get();
        $result = [];

        foreach ($userScores as $userScore) {
            $accumulativeScore = new AccumulativeUserScore($userScore);
            $accumulativeScore = $accumulativeScore->score();
            array_push($result, $accumulativeScore);
        }

        return Response::success($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $middleTestRules = $this->getIncrementName("middle_test_", "required|integer");
        $livelinessRules = $this->getIncrementName("liveliness_", "required|integer");

        $rules = [
            "pre_test"          => "required|integer",
            "post_test"         => "required|integer",
            "user_id"           => "required|exists:users,id|unique:user_scores,user_id"
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
                        "score"         => ($request->$name + 30),
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
        $result = new AccumulativeUserScore($result);
        $result = $result->score();

        return Response::success($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function updateCognitiveScore(Request $request, UserScore $userScore)
    {
        $middleTestRules = $this->getIncrementName("middle_test_", "required|integer");
        $rules = [
            "pre_test"  => "required|integer",
            "post_test" => "required|integer"
        ];
        $rules += $middleTestRules;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return Response::errors($validator);

        $userScore->pre_test = $request->pre_test;
        $userScore->post_test = $request->post_test;

        return DB::transaction(function () use ($userScore, $request) {
            try {
                $userScore->save();

                for ($i = 1; $i <= 10; $i++) {
                    $middleTest = MiddleTest::where("user_score_id", $userScore->id)->where("no_materi", $i)->first();
                    if ($middleTest) {
                        $name = "middle_test_{$i}";
                        $middleTest->score = $request->$name;
                        $middleTest->save();
                    }
                }

                DB::commit();

                $result = UserScore::with(["user", "middletest", "liveliness"])->find($userScore->id);
                $result = new AccumulativeUserScore($result);
                $result = $result->score();

                return Response::success($result);
            } catch (Exception $e) {
                DB::rollBack();
                return Response::message($e->getMessage(), 500);
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFoul  $userFoul
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserScore $userScore)
    {
        $userFouls = UserFoul::where("user_id", $userScore->user_id)->get();

        return DB::transaction(function () use ($userFouls, $userScore) {
            try {
                foreach ($userFouls as $foul) {
                    $foul->delete();
                }
                $userScore->delete();

                DB::commit();
                return Response::success($userScore);
            } catch (Exception $e) {
                DB::rollBack();
                return Response::message($e->getMessage(), 500);
            }
        });
    }

    private function getIncrementName(string $initialName, string $value = "", int $max = 10, int $start = 1): array
    {
        $result = [];
        for ($i = $start; $i <= $max; $i++) {
            $result += [$initialName . "$i" => $value];
        }

        return $result;
    }
}
