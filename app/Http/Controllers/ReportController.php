<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Report;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Training $training)
    {
        $users = User::with(["report", "score"])->where("training_id", $training->id)->get();
        return Response::success($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "score_id"  => "required|exists:scores,id",
            "user_id"   => "required|exists:users,id",
            "status"    => [
                "required",
                Rule::in(["Lulus", "Lulus Bersyarat", "Tidak Lulus"])
            ]
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $report = Report::updateOrCreate(
            ["user_id" => $request->user_id],
            [
                "status" => $request->status,
                "score_id"  => $request->score_id
            ]
        );
        return Response::success($report);
    }
}
