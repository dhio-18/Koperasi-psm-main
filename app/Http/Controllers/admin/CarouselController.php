<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = CarouselImage::orderBy('order')->get();
        return view('pages.admin.carousel', compact('carousels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            // Simpan gambar
            $path = $request->file('image')->store('carousel', 'public');

            // Dapatkan order terakhir
            $lastOrder = CarouselImage::max('order') ?? 0;

            // Simpan ke database
            CarouselImage::create([
                'image_path' => $path,
                'order' => $lastOrder + 1,
                'is_active' => true,
            ]);

            return redirect()->route('admin.carousel.index')
                ->with('success', 'Gambar carousel berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan gambar: ' . $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            $carousel = CarouselImage::findOrFail($id);
            $carousel->is_active = !$carousel->is_active;
            $carousel->save();

            return redirect()->route('admin.carousel.index')
                ->with('success', 'Status gambar berhasil diubah');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status gambar');
        }
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:carousel_images,id',
        ]);

        try {
            foreach ($request->orders as $index => $id) {
                CarouselImage::where('id', $id)->update(['order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan gambar berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan gambar'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $carousel = CarouselImage::findOrFail($id);

            // Hapus file gambar
            if (Storage::disk('public')->exists($carousel->image_path)) {
                Storage::disk('public')->delete($carousel->image_path);
            }

            // Hapus dari database
            $carousel->delete();

            return redirect()->route('admin.carousel.index')
                ->with('success', 'Gambar carousel berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus gambar');
        }
    }
}
