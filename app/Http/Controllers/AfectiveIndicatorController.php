<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\AfectiveIndicator;
use Illuminate\Http\Request;

class AfectiveIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = AfectiveIndicator::with("category")->get();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AfectiveIndicator  $afectiveIndicator
     * @return \Illuminate\Http\Response
     */
    public function show(AfectiveIndicator $afectiveIndicator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AfectiveIndicator  $afectiveIndicator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AfectiveIndicator $afectiveIndicator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AfectiveIndicator  $afectiveIndicator
     * @return \Illuminate\Http\Response
     */
    public function destroy(AfectiveIndicator $afectiveIndicator)
    {
        //
    }
}
