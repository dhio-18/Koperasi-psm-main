@extends('layouts.layout')

@section('title')
    <title>Tentang Kami</title>
@endsection

@section('main')
    <div class="bg-gray-50 font-sans">
        <div class="min-h-screen flex flex-col">

            <!--  HERO (dipadatkan) -->
            <section
                class="bg-gradient-to-br from-green-600 to-green-700 text-white rounded-[1.75rem] shadow-md mx-4 md:mx-6 lg:mx-10 mt-16 mb-8">
                <div class="max-w-6xl mx-auto px-4 py-14 md:py-16 text-center flex flex-col items-center">
                    <div class="space-y-4 max-w-2xl">
                        <h1 class="text-[28px] md:text-[36px] font-bold leading-tight">
                            Tentang Koperasi PSM
                        </h1>

                        <p class="text-base md:text-[17px] text-white/90 leading-relaxed">
                            Waserda Koperasi PSM menyediakan kebutuhan harian karyawan dengan harga terjangkau,
                            transparan, dan dikelola bersama.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center justify-center bg-white text-green-700 font-semibold px-4 py-2.5 rounded-lg shadow hover:shadow-md transition">
                                Belanja Sekarang
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>

                            <a href="#jam-op"
                                class="inline-flex items-center justify-center bg-white/10 border border-white/30 text-white font-medium px-4 py-2.5 rounded-lg hover:bg-white/20 transition">
                                Jam Operasional
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!--  PROFIL / SIAPA KAMI (dipadatkan) -->
            <section class="bg-gray-50 py-12 md:py-14 px-4">
                <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">

                    <!-- Deskripsi koperasi -->
                    <div
                        class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 md:p-8 flex flex-col justify-center">
                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">Siapa Kami?</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Koperasi PSM adalah koperasi yang fokus menyediakan kebutuhan pokok,
                            barang harian, dan layanan simpan-pinjam yang aman, mudah, dan saling menguntungkan.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Kami berkomitmen pada prinsip kebersamaan: keuntungan kembali lagi kepada anggota.
                        </p>
                    </div>

                    <!-- Komitmen -->
                    <div class="flex flex-col gap-4 md:gap-5">
                        <div class="rounded-2xl bg-white border border-gray-200 shadow-sm p-6">
                            <div class="flex items-start gap-3.5">
                                <div class="bg-green-100 text-green-700 rounded-xl p-2.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 .895-3 2v2c0 1.105 1.343 2 3 2s3 .895 3 2v2m-3-6v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-800 font-semibold leading-snug">Komitmen Kami</p>
                                    <p class="text-gray-600 text-sm leading-relaxed mt-1">
                                        Harga transparan, kualitas terjaga, dan pelayanan yang tulus
                                        serta berorientasi pada kesejahteraan anggota.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!--  VISI & MISI (dipadatkan) -->
            <section class="bg-white py-12 md:py-14 px-4 rounded-2xl mx-4 md:mx-6 lg:mx-10 shadow-sm mb-8">
                <div class="max-w-5xl mx-auto">
                    <div class="text-center mb-8 md:mb-10">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2.5">
                            Visi & Misi
                        </h2>
                        <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                            Arah gerak koperasi kami dalam melayani anggota.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 md:gap-7">
                        <!-- Visi -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-7">
                            <div class="flex items-start gap-3.5">
                                <div class="bg-green-100 text-green-700 rounded-xl p-3 flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">Visi</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Menjadi koperasi yang sehat, modern, dan terpercaya dalam memenuhi kebutuhan
                                        anggota.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Misi -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-7">
                            <div class="flex items-start gap-3.5">
                                <div class="bg-green-100 text-green-700 rounded-xl p-3 flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">Misi</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Memberikan produk yang aman, terjangkau, dan mudah diakses,
                                        serta meningkatkan kesejahteraan anggota melalui pelayanan koperasi yang
                                        profesional.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- JAM OPERASIONAL (dipadatkan + auto-center scroll) -->
            <section id="jam-op"
                class="px-4 mx-4 md:mx-6 lg:mx-10 mb-14 mt-4 scroll-mt-24 sm:scroll-mt-28 lg:scroll-mt-32">
                <div class="max-w-5xl mx-auto">
                    <div
                        class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-7 text-center text-sm md:text-[15px]">
                        <p class="text-gray-900 font-semibold text-lg mb-3">Jam Operasional</p>
                        <ul class="space-y-1.5 text-gray-600">
                            <li>Senin - Jumat : 08.00 - 17.00</li>
                            <li>Sabtu : 08.00 - 12.00</li>
                            <li>Minggu / Libur Nasional : Tutup</li>
                        </ul>
                    </div>
                </div>
            </section>


        </div>
    </div>
@endsection
