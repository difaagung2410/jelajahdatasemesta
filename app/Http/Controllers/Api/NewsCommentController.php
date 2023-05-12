<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NewsCommentController extends Controller
{
    /** method for create comment on news */
    public function store(Request $request)
    {
        $data_validate = $request->validate([
            'comment' => ['required', 'string', 'max:255'],
            'news_id' => ['required', 'numeric']
        ]);
        try {
            // Mencari berita yang dimaksud dalam database
             $news = News::find($request->news_id);

             // Menambahkan komen ke tabel comments yang berelasi dengan tabel news
             $news->comments()->create(Arr::except($data_validate, ['news_id']) + [
                'user_id' => auth()->user()->id,
                'user_name' => auth()->user()->name
             ]);

             return response()->json([
                'status' => 'success',
                'message' => 'Successfully create comment',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed create comment',
            ], 500);
        }
    }
}
