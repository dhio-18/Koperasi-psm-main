<?php

namespace App\Http\Controllers\superAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentAccountController extends Controller
{
    /**
     * Tampilkan semua akun pembayaran
     */
    public function index()
    {
        $paymentAccounts = PaymentAccounts::where('is_active', true)->latest()->get();

        // Siapkan data untuk dikirim ke Blade dalam bentuk array siap pakai
        $accounts = $paymentAccounts->map(function ($a) {
            return [
                'id' => $a->id,
                'bank_name' => $a->bank_name,
                'account_number' => $a->account_number,
                'account_holder_name' => $a->account_holder_name,
                'qr_code_url' => $a->qr_code_path ? asset('storage/' . $a->qr_code_path) : null,
            ];
        })->values();

        return view('pages.superAdmin.paymentAccount', [
            'paymentAccounts' => $paymentAccounts,
            'accounts' => $accounts,
        ]);
    }

    /**
     * Tambah akun pembayaran baru
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'bank_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50|unique:payment_accounts,account_number',
                'account_holder_name' => 'required|string|max:255',
                'qr_code' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            ]);

            $path = null;
            if ($request->hasFile('qr_code')) {
                $path = $request->file('qr_code')->store('payment_qr', 'public');
            }

            PaymentAccounts::create([
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder_name' => $data['account_holder_name'],
                'is_active' => true,
                'qr_code_path' => $path,
            ]);

            DB::commit();
            return back()->with('success', 'Payment account berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update akun pembayaran
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $account = PaymentAccounts::findOrFail($id);

            $data = $request->validate([
                'bank_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50|unique:payment_accounts,account_number,' . $account->id,
                'account_holder_name' => 'required|string|max:255',
                'qr_code' => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
                'remove_qr' => 'nullable|boolean',
            ]);

            // Hapus QR jika user minta hapus atau upload baru
            if ($request->boolean('remove_qr') || $request->hasFile('qr_code')) {
                if ($account->qr_code_path) {
                    Storage::disk('public')->delete($account->qr_code_path);
                }
                $account->qr_code_path = null;
            }

            // Upload QR baru kalau ada file baru
            if ($request->hasFile('qr_code')) {
                $account->qr_code_path = $request->file('qr_code')->store('payment_qr', 'public');
            }

            $account->update([
                'bank_name' => $data['bank_name'],
                'account_number' => $data['account_number'],
                'account_holder_name' => $data['account_holder_name'],
                'qr_code_path' => $account->qr_code_path,
            ]);

            DB::commit();
            return back()->with('success', 'Payment account berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus akun pembayaran
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $account = PaymentAccounts::findOrFail($id);

            if ($account->qr_code_path) {
                Storage::disk('public')->delete($account->qr_code_path);
            }

            $account->delete();

            DB::commit();
            return back()->with('success', 'Payment account berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
