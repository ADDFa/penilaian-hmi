<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Liveliness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LivelinessController extends Controller
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
        $validator = Validator::make($request->all(), [
            "score_id"  => "required|exists:scores,id",
            "score" => "required|integer"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $liveliness = new Liveliness($validator->validate());
        $liveliness->save();

        return Response::success($liveliness);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Liveliness  $liveliness
     * @return \Illuminate\Http\Response
     */
    public function show(Liveliness $liveliness)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Liveliness  $liveliness
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Liveliness $liveliness)
    {
        $validator = Validator::make($request->all(), [
            "score" => "required|integer"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $liveliness->update($validator->validate());
        return Response::success($liveliness);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Liveliness  $liveliness
     * @return \Illuminate\Http\Response
     */
    public function destroy(Liveliness $liveliness)
    {
        $liveliness->delete();
        return Response::success($liveliness);
    }
}
