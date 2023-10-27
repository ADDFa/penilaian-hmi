<?php

namespace App\Http;

class Response
{
    public static function success(mixed $data)
    {
        return response()->json($data);
    }

    public static function errors(\Illuminate\Validation\Validator $validator)
    {
        return response()->json(["errors" => $validator->errors()], 400);
    }

    public static function message(string $message, int $status = 200)
    {
        return response()->json(["message" => $message], $status);
    }
}
