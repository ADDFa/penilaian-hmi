<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return Response::success($users);
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
            "nama"  => "required|string"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $user = new user($request->all());
        $user->save();

        return Response::success($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        return Response::success($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        $validator = Validator::make($request->all(), [
            "nama"  => "required|string"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $user->nama = $request->nama;
        if ($request->asal) $user->asal = $request->asal;
        $user->save();

        return Response::success($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        $user->delete();
        return Response::success($user);
    }
}
