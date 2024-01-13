<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Response;
use App\Models\AfectiveIndicator;
use App\Models\AfectiveIndicatorScore;
use App\Models\Score;
use App\Models\User;
use App\Models\UserFoul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserFoulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id"   => "required|exists:users,id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $result = UserFoul::with("afectiveIndidator")->get();
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
        $validator = Validator::make($request->all(), [
            "user_id"   => "required|exists:users,id",
            "afective_indicator_id" => "required|exists:afective_indicators,id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        return DB::transaction(function () use ($validator, $request) {
            try {
                $user = User::find($request->user_id);
                if (!$user->training_id) return Response::message("Not Participant Test!", 400);

                $indicator = AfectiveIndicator::with("category")->find($request->afective_indicator_id);
                $deductionPoints = $indicator->poin_pengurangan;

                $score = Score::where("user_id", $user->id)->first();
                $score->afektif_1 -= $deductionPoints;
                $score->save();

                $afectiveIndicatorScore = AfectiveIndicatorScore::where("score_id", $score->id)->first();
                switch ($indicator->category->category) {
                    case "Ketepatan Waktu":
                        $afectiveIndicatorScore->ketepatan_waktu -= $deductionPoints;
                        break;

                    case "Tingkah Laku":
                        $afectiveIndicatorScore->tingkah_laku -= $deductionPoints;
                        break;

                    case "Tata Bahasa":
                        $afectiveIndicatorScore->tata_bahasa -= $deductionPoints;
                        break;

                    case "Pakaian":
                        $afectiveIndicatorScore->pakaian -= $deductionPoints;
                        break;
                }
                $afectiveIndicatorScore->save();

                $userFoul = new UserFoul($validator->validate());
                $userFoul->score_id = $score->id;
                $userFoul->save();
                DB::commit();

                return Response::success($userFoul);
            } catch (Exception $e) {
                DB::rollBack();
                return Response::message($e->getMessage(), 500);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserFoul  $userFoul
     * @return \Illuminate\Http\Response
     */
    public function show(UserFoul $userFoul)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserFoul  $userFoul
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFoul $userFoul)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserFoul  $userFoul
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFoul $userFoul)
    {
        return DB::transaction(function () use ($userFoul) {
            try {
                $score = Score::find($userFoul->score_id);
                $indicator = AfectiveIndicator::find($userFoul->afective_indicator_id);

                $deductionPoints = $indicator->poin_pengurangan;
                $score->afektif_1 += $deductionPoints;

                $afectiveIndicatorScore = AfectiveIndicatorScore::where("score_id", $score->id)->first();
                switch ($indicator->category->category) {
                    case "Ketepatan Waktu":
                        $afectiveIndicatorScore->ketepatan_waktu += $deductionPoints;
                        break;

                    case "Tingkah Laku":
                        $afectiveIndicatorScore->tingkah_laku += $deductionPoints;
                        break;

                    case "Tata Bahasa":
                        $afectiveIndicatorScore->tata_bahasa += $deductionPoints;
                        break;

                    case "Pakaian":
                        $afectiveIndicatorScore->pakaian += $deductionPoints;
                        break;
                }

                $afectiveIndicatorScore->save();
                $score->save();
                $userFoul->delete();

                DB::commit();
                return Response::success($userFoul);
            } catch (Exception $e) {

                DB::rollBack();
                return Response::message($e->getMessage(), 500);
            }
        });
    }
}
