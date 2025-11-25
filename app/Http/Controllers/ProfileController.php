<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use App\Models\Orders;
use App\Models\Returns;
use App\Models\Shipments;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\UserAdresses;
use App\Services\FileUploadService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('pages.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|numeric',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
        ]);

        try {
            DB::beginTransaction();

            // Siapkan data untuk update
            $dataToUpdate = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];

            // Proses upload foto jika ada
            if ($request->hasFile('profile_photo')) {
                $fileUploadService = new FileUploadService();

                // Upload dan dapatkan path relatif (contoh: 'profile/xxxxx.jpg')
                $photoPath = $fileUploadService->upload(
                    $request,
                    'profile_photo',
                    'profile',
                    $user->profile_photo_path
                );

                // Tambahkan ke data update
                $dataToUpdate['profile_photo_path'] = $photoPath;

                // Debug log (opsional, bisa dihapus setelah fix)
                \Log::info('Photo uploaded - Path: ' . $photoPath);
            }

            // Update user
            $user->update($dataToUpdate);

            DB::commit();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * index alamat
     */

    public function address()
    {
        $data = UserAdresses::where('user_id', Auth::id())->get();
        return view('pages.profile.address', compact('data'));
    }

    /**
     * Tambah alamat
     */
    public function addAddress(Request $request)
    {
        // Validasi data sesuai dengan form Anda
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:60',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:1000',
            'house_number' => [
                'nullable',
                'string',
                'max:10',
                function ($attribute, $value, $fail) use ($request) {
                    if (stripos($request->label, 'kantor') === false && empty($value)) {
                        $fail('Nomor rumah/gedung wajib diisi untuk alamat kantor');
                    }
                },
            ],
        ], [
            'label.required' => 'Label alamat wajib diisi',
            'label.max' => 'Label alamat maksimal 20 karakter',
            'recipient_name.required' => 'Nama penerima wajib diisi',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.max' => 'Nomor telepon maksimal 15 karakter',
            'address.required' => 'Detail alamat wajib diisi',
            'address.max' => 'Detail alamat maksimal 1000 karakter',
            'house_number.max' => 'Nomor rumah/gedung maksimal 10 karakter',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan dalam pengisian form.');
        }

        try {

            DB::beginTransaction();
            // Data provinsi dan kabupaten dari form (hardcoded)
            $province = 'Lampung';
            $regency = 'Way Kanan';
            $district = 'Pakuan Ratu';
            $postalCode = '34762';

            // Gabungkan alamat lengkap
            $fullAddress = $request->label .
                (!empty($request->house_number) ? ', ' . $request->house_number : '') .
                (!empty($request->address) ? ', ' . $request->address : '') .
                ', ' . $district . ', ' . $regency . ', ' . $province . ', ' . $request->postal_code;

            // Simpan alamat ke database
            $address = new UserAdresses();
            $address->user_id = Auth::id();
            $address->label = $request->label;
            $address->house_number = $request->house_number;
            $address->full_address = $fullAddress;
            $address->recipient_name = $request->recipient_name;
            $address->phone = $request->phone;
            $address->address = $request->address;
            $address->postal_code = $postalCode;
            $address->save();

            DB::commit();

            return redirect()->back()->with('success', 'Alamat berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateAddress(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $address = UserAdresses::findOrFail($id);

            $validated = $request->validate([
                'label' => 'required|string|max:60',
                'house_number' => [
                    'nullable',
                    'string',
                    'max:10',
                    function ($attribute, $value, $fail) use ($request) {
                        if (stripos($request->label, 'kantor') === false && empty($value)) {
                            $fail('Nomor rumah/gedung wajib diisi untuk alamat selain kantor');
                        }
                    },
                ],
                'phone' => 'required|string|max:15',
                'recipient_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
            ], [
                'label.required' => 'Label alamat harus diisi',
                'label.max' => 'Label alamat maksimal 20 karakter',
                'phone.required' => 'Nomor telepon harus diisi',
                'phone.max' => 'Nomor telepon maksimal 15 karakter',
                'recipient_name.required' => 'Nama penerima harus diisi',
                'recipient_name.max' => 'Nama penerima maksimal 255 karakter',
                'address.required' => 'Detail alamat harus diisi',
                'address.max' => 'Detail alamat maksimal 500 karakter',
            ]);

            // Add default province and district
            $validated['province'] = 'Lampung';
            $validated['regency'] = 'Way Kanan';
            $validated['district'] = 'Pakuan Ratu';
            $validated['postal_code'] = '34762';
            // Gabungkan alamat lengkap
            $validated['full_address'] = $validated['label'] .
                (!empty($validated['house_number']) ? ', ' . $validated['house_number'] : '') .
                (!empty($validated['address']) ? ', ' . $validated['address'] : '') .
                ', ' . $validated['district'] . ', ' . $validated['regency'] . ', ' . $validated['province'] . ', ' . $validated['postal_code'];

            $address->update($validated);

            DB::commit();

            return redirect()->back()->with('success', 'Alamat berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus alamat
     */
    public function delAddress($id)
    {
        try {
            DB::beginTransaction();

            $address = UserAdresses::where('user_id', Auth::id())->findOrFail($id);
            $address->delete();

            DB::commit();
            return redirect()->back()
                ->with('success', 'Alamat berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function orders()
    {
        $orders = Orders::with([
            'orderItems.products',
            'returns',  // Load semua returns termasuk yang ditolak
            'shipment',
            'histories',
        ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                $order->date = Carbon::parse($order->created_at)->format('d-m-Y');
                $order->time = Carbon::parse($order->created_at)->format('H:i');

                // Pastikan returns di-load dan ter-serialize dengan benar
                if ($order->returns) {
                    $order->returns = $order->returns->map(function ($return) {
                        return [
                            'id' => $return->id,
                            'reason' => $return->reason,
                            'comments' => $return->comments,
                            'images' => $return->images,
                            'status' => $return->status,
                            'admin_notes' => $return->admin_notes, // Pastikan ini ter-include
                            'processed_by' => $return->processed_by,
                            'processed_at' => $return->processed_at,
                            'created_at' => $return->created_at,
                        ];
                    })->toArray();
                }

                // Include auto-confirm fields
                $order->auto_confirmed = (bool) $order->auto_confirmed;
                $order->auto_confirmed_at = $order->auto_confirmed_at ? Carbon::parse($order->auto_confirmed_at)->format('d-m-Y H:i') : null;

                return $order;
            });

        return view('pages.profile.myOrders', compact('orders'));
    }

    public function completeOrder($id)
    {
        try {
            DB::beginTransaction();

            $order = Orders::where('user_id', Auth::id())->findOrFail($id);
            $order->status = 'completed'; // Atur status menjadi 'completed'
            $order->save();

            Shipments::create([
                'order_id' => $order->id,
                'status' => 'delivered',
                'delivered_at' => Carbon::now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            OrderHistory::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'action' => 'completed',
                'description' => 'Pesanan telah diterima oleh ' . Auth::user()->name,
            ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Pesanan berhasil diselesaikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function returnOrder(Request $request, $id)
{
    $request->validate([
        'reason' => 'required|in:defective,wrong_item,other',
        'comments' => 'nullable|string|max:1000',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:5120', // max 5MB per image
    ], [
        'reason.required' => 'Alasan pengembalian harus dipilih',
        'reason.in' => 'Alasan pengembalian tidak valid',
        'comments.max' => 'Catatan maksimal 1000 karakter',
        'images.*.image' => 'File harus berupa gambar',
        'images.*.mimes' => 'Format gambar harus jpeg, png, atau jpg',
        'images.*.max' => 'Ukuran gambar maksimal 5MB',
    ]);

    try {
        DB::beginTransaction();

        $order = Orders::where('user_id', Auth::id())->findOrFail($id);

        // Upload multiple images menggunakan FileUploadService
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            $fileUploadService = new FileUploadService();
            $uploadedImages = $fileUploadService->uploadMultiple($request, 'images', 'returns');
        }

        // Create return record dengan images sebagai JSON
        Returns::create([
            'reason' => $request->reason,
            'comments' => $request->comments,
            'images' => $uploadedImages, // Laravel otomatis encode ke JSON
            'status' => 'pending',
            'order_id' => $order->id,
            'user_id' => Auth::id(),
        ]);

        // Create order history
        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'returned',
            'description' => 'Pengajuan pengembalian oleh ' . Auth::user()->name,
        ]);

        // Update order status
        $order->update(['status' => 'returned']);

        DB::commit();

        return redirect()->back()->with('success', 'Pengembalian berhasil dikirim!');
    } catch (\Exception $e) {
        DB::rollBack();

        // Log error untuk debugging
        \Log::error('Return order error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}
}
