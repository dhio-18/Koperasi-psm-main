@extends('layouts.layout')

@section('title')
    <title>Tata Cara Pemesanan</title>
@endsection

@section('main')
<div class="bg-gray-50 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- HERO SECTION -->
        <section class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-[1.75rem] shadow-md mx-4 md:mx-6 lg:mx-10 mt-16 mb-8">
            <div class="max-w-6xl mx-auto px-4 py-14 md:py-16 text-center flex flex-col items-center">
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4 mx-auto">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h1 class="text-[28px] md:text-[36px] font-bold leading-tight">
                        Tata Cara Pemesanan
                    </h1>
                    <p class="text-base md:text-[17px] text-white/90 leading-relaxed">
                        Panduan lengkap untuk melakukan pemesanan di Koperasi PSM dengan mudah dan cepat
                    </p>
                </div>
            </div>
        </section>

        <!-- MAIN CONTENT -->
        <section class="max-w-6xl mx-auto px-4 md:px-6 lg:px-10 py-8 md:py-12 flex-grow">
            <!-- Steps Container -->
            <div class="space-y-6 mb-12">
                <!-- Step 1 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            1
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Buat Akun atau Login</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Pertama, buat akun baru dengan menggunakan email Anda. Jika sudah memiliki akun, cukup login dengan email dan password Anda. Anda juga dapat login menggunakan akun Google untuk kemudahan akses.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            2
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Edit Profil dan Alamat</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Setelah login, segera masuk ke halaman profil Anda. Lengkapi data diri dengan menambahkan nomor telepon yang aktif dan alamat rumah/kantor Anda dengan detail yang akurat. Data ini sangat penting untuk proses pengiriman dan komunikasi dengan tim kami.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            3
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Jelajahi Produk</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Kunjungi halaman produk untuk melihat semua produk yang tersedia. Anda dapat mencari produk berdasarkan kategori, mengurutkan berdasarkan harga tertinggi terendah atau nama a-z z-a, dan melihat detail lengkap dari setiap produk termasuk deskripsi dan harga.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            4
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Tambahkan ke Keranjang</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Setelah menemukan produk yang diinginkan, pilih jumlah produk yang ingin dibeli dan klik tombol "Tambah ke Keranjang". Anda dapat menambahkan beberapa produk sekaligus ke keranjang belanja Anda.
                        </p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            5
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Review Keranjang Belanja</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Buka halaman keranjang belanja Anda untuk melihat semua produk yang telah ditambahkan. Periksa kembali jumlah, harga, dan total pesanan. Anda dapat mengubah jumlah produk atau menghapus produk jika diperlukan sebelum melanjutkan checkout.
                        </p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            6
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Masukkan Alamat Pengiriman</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Pada proses checkout, masukkan atau pilih alamat pengiriman Anda. Pastikan alamat yang Anda berikan sudah benar dan lengkap agar paket dapat sampai dengan lancar. Anda dapat menyimpan beberapa alamat untuk memudahkan pemesanan berikutnya.
                        </p>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            7
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Pilih Metode Pembayaran</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Pilih metode pembayaran yang Anda inginkan dari berbagai opsi yang tersedia seperti transfer bank, e-wallet, atau pembayaran lainnya. Sistem kami mendukung berbagai metode pembayaran untuk kemudahan Anda.
                        </p>
                    </div>
                </div>

                <!-- Step 8 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            8
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Proses Pembayaran</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Ikuti instruksi pembayaran sesuai dengan metode pembayaran yang Anda pilih. Pastikan Anda menyelesaikan pembayaran dalam waktu yang ditentukan agar pesanan Anda diproses dengan cepat.
                        </p>
                    </div>
                </div>

                <!-- Step 9 -->
                <div class="flex gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-green-600 text-white font-bold text-lg">
                            9
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">Konfirmasi dan Pengiriman</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Setelah pembayaran berhasil, pesanan Anda akan diproses oleh tim kami. Anda akan menerima notifikasi melalui pesananku ketika pesanan telah dikirim. Anda dapat melacak status pengiriman Anda melalui halaman "Pesananku" di profil Anda.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="bg-green-50 border-l-4 border-green-600 p-6 rounded-r-lg mb-12">
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tips & Trik Berbelanja
                </h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm md:text-base">Periksa ketersediaan stok produk sebelum melakukan pemesanan</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm md:text-base">Lengkapi data profil Anda untuk proses checkout yang lebih cepat</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm md:text-base">Pastikan alamat pengiriman sudah benar sebelum menyelesaikan pesanan</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm md:text-base">Simpan nomor resi pengiriman untuk referensi Anda</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm md:text-base">Hubungi layanan pelanggan kami jika ada pertanyaan atau kendala</span>
                    </li>
                </ul>
            </div>

            <!-- CTA Button -->
            <div class="text-center mb-8">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 md:px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-300 gap-2 text-sm md:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Mulai Belanja Sekarang
                </a>
            </div>
        </section>
    </div>
</div>
@endsection
