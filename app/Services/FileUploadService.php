<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadservice
{
    /**
     * Upload file dinamis.
     *
     * @param Request $request
     * @param string $field nama input file di form
     * @param string $folder nama folder penyimpanan di storage
     * @param string|null $oldFile path lama (optional, untuk dihapus)
     * @return string|null path baru atau null jika tidak ada file
     */

    public function upload(Request $request, string $field, string $folder, string $oldFile = null)
    {
        if ($request->hasFile($field)) {
            // Hapus file lama jika ada
            if ($oldFile && Storage::exists($oldFile)) {
                Storage::delete($oldFile);
            }

            // simpan file baru
            $path = $request->file($field)->store($folder, 'public');
            return 'storage/' . $path;
        }

        return $oldFile; // kembalikan path lama jika tidak ada file baru
    }
}