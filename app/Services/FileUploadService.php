<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload file dan return path relatif
     * 
     * @param Request $request
     * @param string $fieldName - nama field input file
     * @param string $folder - folder tujuan dalam storage/app/public/
     * @param string|null $oldPath - path file lama yang akan dihapus
     * @return string|null - return path relatif (contoh: 'profile/xxxxx.jpg')
     */
    public function upload($request, $fieldName, $folder, $oldPath = null)
    {
        // Jika tidak ada file yang diupload, return path lama
        if (!$request->hasFile($fieldName)) {
            return $oldPath;
        }

        // Hapus file lama jika ada
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
        
        // Upload file baru ke storage/app/public/{folder}
        $file = $request->file($fieldName);
        $path = $file->store($folder, 'public');
        
        // Return path relatif saja, BUKAN full URL
        // Contoh return: 'profile/xxxxx.jpg'
        return $path;
    }
}