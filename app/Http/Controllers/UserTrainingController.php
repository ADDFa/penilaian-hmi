<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\AfektifIndicatorScore;
use App\Models\Score;
use App\Models\Training;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $training = Training::find($request->training_id);
        $users = User::with(["score", "score.afectiveScore"])->where("training_id", $request->training_id)->get();

        return Response::success([
            "training"  => $training,
            "users"     => $users
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
            "training_id"   => "required|exists:trainings,id",
            "users"         => "array|required",
            "users.*"       => "integer|exists:users,id"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $results = [];
        return DB::transaction(function () use ($request, $results) {
            try {
                foreach ($request->users as $user_id) {
                    $user = User::find($user_id);
                    $user->training_id = $request->training_id;
                    $user->save();

                    $score = Score::firstOrCreate(["user_id" => $user_id]);
                    AfektifIndicatorScore::firstOrCreate(["score_id" => $score->id]);

                    $score = Score::with("afectiveScore")->find($score->id);
                    array_push($results, [
                        "user"  => $user->toArray(),
                        "score" => $score->toArray()
                    ]);

                    DB::commit();
                }
                return Response::success($results);
            } catch (Exception $e) {
                DB::rollBack();
                return Response::message("Gagal menambahkan peserta pelatihan! {$e->getMessage()}", 500);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user = $user->with("training")->where("training_id", "<>", null)->find($user->id);
        return Response::success($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->training_id = null;
        $user->save();

        $score = Score::where("user_id", $user->id)->first();
        if ($score) $score->delete();

        return Response::success($user);
    }
}
