<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsLog;
use Illuminate\Http\Request;

class NewsLogController extends Controller
{
    /** method for middleware for validation user login admin or not admin */
    public function __construct()
    {
        $this->middleware('is.admin:true', ['only' => ['index']]);
    }

    /** method for show list news logs with pagination */
    public function index(Request $request)
    {
        try {
            // Mengambil data log (histori) berita lalu diberi halaman
            $news_logs = NewsLog::paginate($request->pages ?? 10);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully retrieve all news logs',
                'data' => $news_logs
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed retrieve news logs',
            ], 500);
        }
    }
}
