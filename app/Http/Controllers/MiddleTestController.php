<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\MiddleTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MiddleTestController extends Controller
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

        $middleTest = new MiddleTest($validator->validate());
        $middleTest->save();

        return Response::success($middleTest);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MiddleTest  $middleTest
     * @return \Illuminate\Http\Response
     */
    public function show(MiddleTest $middleTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MiddleTest  $middleTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MiddleTest $middleTest)
    {
        $validator = Validator::make($request->all(), [
            "score" => "required|integer"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $middleTest->update($validator->validate());
        return Response::success($middleTest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MiddleTest  $middleTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(MiddleTest $middleTest)
    {
        $middleTest->delete();
        return Response::success($middleTest);
    }
}
