<?php

namespace App\Http\Controllers\Api;

use App\Events\NewsHistory;
use App\Http\Controllers\Controller;
use App\Http\Traits\UploadTraits;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class NewsController extends Controller
{
    use UploadTraits;

    /** method for middleware for validation user login admin or not admin */
    public function __construct()
    {
        $this->middleware('is.admin:true', ['only' => ['store','update', 'destroy']]);
    }

    /** method for show list news with pagination */
    public function index(Request $request)
    {
        try {
            // Mengambil data berita lalu diberi halaman
            $news = News::paginate($request->pages ?? 10);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully retrieve all news',
                'data' => $news
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed retrieve news',
            ], 500);
        }
    }

    /** method for create comment on news */
    public function store(Request $request)
    {
        // Validasi input tambah berita yang sudah dimasukkan oleh admin
        $data_validated = $request->validate([
            'photo' => ['nullable', 'file'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string']
        ]);
        try {
            // Menambahkan data berita yang sudah divalidasi sebelumnya 
            $news = News::create(Arr::except($data_validated, ['photo']) + [
                'slug' => Str::slug($request->title)
            ]);

            // Jika admin upload foto berita
            if ($request->photo) {
                $news->update([
                    'photo_path' => $this->uploadFile($request->photo), // Method upload foto yang ada di uploadTrait
                    'photo_name' => $request->photo->getClientOriginalName(), // Mengambil nama file tanpa ekstensi
                ]);
            }

            // Event & Listener ketika menambahkan berita
            event(new NewsHistory($news, 'Create'));

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully create news',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed create news',
            ], 500);
        }
    }

    /** method for showing detail news with comment */
    public function edit($news)
    {
        try {
            // Mencari berita, bisa menggunakan news_id atau menggunakan slug
            $news_find = News::with('comments')->where('id', $news)->orWhere('slug', $news)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully retrieve news',
                'data' => $news_find
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed retrieve news',
            ], 500);
        }
    }

    /** method for update news */
    public function update(Request $request, News $news)
    {
        // Validasi input tambah berita yang sudah dimasukkan oleh admin
        $data_validated = $request->validate([
            'photo' => ['nullable', 'file'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string']
        ]);
        try {
            // Mengupdate data yang sudah divalidasi sebelumnya.
            $news->update(Arr::except($data_validated, ['photo']) + [
                'slug' => Str::slug($request->title)
            ]);

            // Jika admin upload foto
            if ($request->photo) {
                // Jika berita yang diupdate memiliki foto sebelumnya, maka foto sebelumnya dihapus dari storage
                if ($news->photo_path) {
                    $this->deleteFile($news->photo_path);
                }

                // Update data foto yang diupload admin
                $news->update([
                    'photo_path' => $this->uploadFile($request->photo),
                    'photo_name' => $request->photo->getClientOriginalName(),
                ]);
            }

            // Event & Listener ketika berita diupdate
            event(new NewsHistory($news, 'Update'));

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully update news',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed update news',
            ], 500);
        }
    }

    /** method for delete news */
    public function destroy(News $news)
    {
        try {
            // Jika berita memiliki komen, maka komen dihapus dari database
            if (count($news->comments)) {
                $news->comments->delete();
            }

            // Jika berita memiliki foto, foto dihapus dari storage
            if ($news->photo_path) {
                $this->deleteFile($news->photo_path);
            }

            // Event & Listener ketika berita dihapus
            event(new NewsHistory($news, 'Delete'));

            // Hapus berita
            $news->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully delete news',
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed delete news',
            ], 500);
        }
    }
}
