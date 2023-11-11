<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Livelines;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ActivityController extends Controller
{
    public function activityToggle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "category"  => [
                "required",
                // GC = Group Control, PS = Problem Solving
                Rule::in(["GC", "PS"])
            ],
            "user_id"   => "required|exists:user_scores,user_id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $userScore = UserScore::where("user_id", $request->user_id)->first();

        switch ($request->category) {
            case "GC":
                $userScore->penguasaan_kelompok = $userScore->penguasaan_kelompok === 0 ? 10 : 0;
                break;

            case "PS":
                $userScore->problem_solving = $userScore->problem_solving === 0 ? 15 : 0;
                break;
        }

        $userScore->save();
        return Response::success($userScore);
    }

    public function liveliness(Request $request, Livelines $liveliness)
    {
        $validator = Validator::make($request->all(), [
            "operation" => Rule::in(["add", "less"])
        ]);
        if ($validator->fails()) return Response::errors($validator);

        if ($request->operation === "less") {
            $liveliness->score -= 10;
        } else {
            $liveliness->score += 10;
        }
        $liveliness->save();

        return Response::success($liveliness);
    }
}
