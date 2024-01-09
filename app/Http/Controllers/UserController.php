<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy("id", "desc");

        if ($request->take) $users->take($request->take);
        if ($request->after) $users->where("id", "<", $request->after);

        return Response::success($users->get());
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
            "nama"  => "required|string",
            "asal"  => "string"
        ]);
        if ($validator->fails()) return Response::errors($validator);

        $user = new User($validator->validate());
        $user->save();

        return Response::success($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return Response::success($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            "nama"  => "required|string",
            "asal"  => "string"
        ]);
        if ($validator->fails()) return  Response::errors($validator);

        $user->update($validator->validate());
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
        $user->delete();
        return Response::success($user);
    }

    public function register(Request $request)
    {
        return $this->store($request);
    }
}
