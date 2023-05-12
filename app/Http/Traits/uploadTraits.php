<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTraits
{
    /**
     * Upload the file with slugging to a given path
     *
     */
    public function uploadFile(UploadedFile $image)
    {
        // Mengambil ekstensi file
        $extension = $image->getClientOriginalExtension();
        // Melakukan hashing nama file agar jika admin upload gambar dengan nama yang sama tidak akan tertimpa
        $image_name = $image->hashName() . $extension;
        // Inisialisasi nama folder untuk menyimpan gambar
        $filePath = Carbon::now()->format('Y/m/d/');
        // Menyimpan gambar di storage
        Storage::disk('public')->putFileAs($filePath, $image, $image_name);

        return $filePath . $image_name;
    }

    /**
     * Delete image with given path
     *
     */
    public function deleteFile($image)
    {
        // Mengecek File apakah ada di dalam storage
        if (Storage::disk('public')->exists($image)) {
            // Hapus file di storage
            Storage::disk('public')->delete($image);
        }
    }
}
