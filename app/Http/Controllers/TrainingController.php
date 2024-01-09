<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainings = Training::orderBy("id", "desc")->get();
        return Response::success($trainings);
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
            "name" => "required|string"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $training = new Training($validator->validate());
        $training->save();

        return Response::success($training);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function destroy(Training $training)
    {
        $training->delete();
        return Response::success($training);
    }
}
