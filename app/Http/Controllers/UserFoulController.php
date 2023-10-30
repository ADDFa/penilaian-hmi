<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Response;
use App\Models\Foul;
use App\Models\User;
use App\Models\UserFoul;
use App\Models\UserScore;
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

        $user = User::find($request->user_id);
        $userFouls = UserFoul::with("foul")->where("user_id", $request->user_id)->get();

        return Response::success([
            "user"          => $user,
            "user_fouls"    => $userFouls
        ]);
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
            "foul_id"   => "required|exists:fouls,id",
            "user_id"   => "required|exists:users,id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $userFoul = new UserFoul($request->all());
        $foul = Foul::find($request->foul_id);
        $userScore = UserScore::where("user_id", $request->user_id)->first();

        switch ($foul->category) {
            case "Ketepatan Waktu":
                $userScore->ketepatan_waktu -= $foul->point;
                break;
            case "Tingkah Laku":
                $userScore->tingkah_laku -= $foul->point;
                break;
            case "Tata Bahasa":
                $userScore->tata_bahasa -= $foul->point;
                break;
            case "Pakaian":
                $userScore->pakaian -= $foul->point;
                break;
            case "Meninggalkan Sholat":
                $userScore->sholat -= $foul->point;
                break;
        }

        return DB::transaction(function () use ($userFoul, $userScore) {
            try {
                $userFoul->save();
                $userScore->save();

                DB::commit();

                return Response::success([
                    "user_foul"     => $userFoul,
                    "user_score"    => $userScore
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return Response::message($e->getMessage(), 500);
            }
        });
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
        $validator = Validator::make($request->all(), [
            "foul_id"   => "required|exists:fouls,id",
            "user_id"   => "required|exists:users,id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $userScore = UserScore::where("user_id", $userFoul->user_id)->first();
        $oldFoul = Foul::find($userFoul->foul_id);
        $foul = Foul::find($request->foul_id);

        $userFoul->foul_id = $request->foul_id;

        switch ($oldFoul->category) {
            case "Ketepatan Waktu":
                $userScore->ketepatan_waktu += $oldFoul->point;
                break;
            case "Tingkah Laku":
                $userScore->tingkah_laku += $oldFoul->point;
                break;
            case "Tata Bahasa":
                $userScore->tata_bahasa += $oldFoul->point;
                break;
            case "Pakaian":
                $userScore->pakaian += $oldFoul->point;
                break;
            case "Meninggalkan Sholat":
                $userScore->sholat += $oldFoul->point;
                break;
        }

        switch ($foul->category) {
            case "Ketepatan Waktu":
                $userScore->ketepatan_waktu -= $foul->point;
                break;
            case "Tingkah Laku":
                $userScore->tingkah_laku -= $foul->point;
                break;
            case "Tata Bahasa":
                $userScore->tata_bahasa -= $foul->point;
                break;
            case "Pakaian":
                $userScore->pakaian -= $foul->point;
                break;
            case "Meninggalkan Sholat":
                $userScore->sholat -= $foul->point;
                break;
        }

        return DB::transaction(function () use ($userScore, $userFoul) {
            try {
                $userScore->save();
                $userFoul->save();
                DB::commit();

                return Response::success([
                    "user_score"    => $userScore,
                    "user_foul"     => $userFoul
                ]);
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
    public function destroy(UserFoul $userFoul)
    {
        $userScore = UserScore::where("user_id", $userFoul->user_id)->first();
        $foul = Foul::find($userFoul->foul_id);

        switch ($foul->category) {
            case "Ketepatan Waktu":
                $userScore->ketepatan_waktu += $foul->point;
                break;
            case "Tingkah Laku":
                $userScore->tingkah_laku += $foul->point;
                break;
            case "Tata Bahasa":
                $userScore->tata_bahasa += $foul->point;
                break;
            case "Pakaian":
                $userScore->pakaian += $foul->point;
                break;
            case "Meninggalkan Sholat":
                $userScore->sholat += $foul->point;
                break;
        }

        return DB::transaction(function () use ($userScore, $userFoul) {
            try {
                $userScore->save();
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
