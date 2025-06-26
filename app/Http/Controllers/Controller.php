<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function respondWithOk($data = [], int $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $status);
    }

    protected function respondWithErrors($errors = [], int $status = 400)
    {
        return response()->json([
            'status' => 'error',
            'errors' => $errors
        ], $status);
    }
}
