<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload single file dan return path relatif
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

    /**
     * Upload multiple files dan return array of paths
     * 
     * @param Request $request
     * @param string $fieldName - nama field input file (contoh: 'images')
     * @param string $folder - folder tujuan dalam storage/app/public/
     * @param array $oldPaths - array path file lama yang akan dihapus
     * @return array - return array path relatif
     */
    public function uploadMultiple($request, $fieldName, $folder, $oldPaths = [])
    {
        // Jika tidak ada file yang diupload, return array kosong
        if (!$request->hasFile($fieldName)) {
            return $oldPaths ?? [];
        }

        // Hapus file lama jika ada
        if (!empty($oldPaths)) {
            foreach ($oldPaths as $oldPath) {
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        $uploadedPaths = [];
        $files = $request->file($fieldName);

        // Handle jika hanya 1 file (bukan array)
        if (!is_array($files)) {
            $files = [$files];
        }

        // Upload setiap file
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $path = $file->store($folder, 'public');
                $uploadedPaths[] = $path;
            }
        }

        return $uploadedPaths;
    }

    /**
     * Hapus file dari storage
     * 
     * @param string|array $paths - path file atau array of paths
     * @return bool
     */
    public function delete($paths)
    {
        if (empty($paths)) {
            return false;
        }

        // Convert ke array jika single path
        if (!is_array($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return true;
    }
}