<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** method for show data user login */
    public function index()
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully retrieve user',
                'data' => auth()->user()
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed retrieve user',
            ], 500);
        }
    }
}
