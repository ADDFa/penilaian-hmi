<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Foul;

class FoulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fouls = Foul::all();
        return Response::success($fouls);
    }
}
