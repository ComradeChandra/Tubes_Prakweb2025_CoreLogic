@extends('layouts.app')

@section('content')
<section class="bg-gray-900 bg-[url('https://images.unsplash.com/photo-1595590424283-b8f17842773f?q=80&w=2070&auto=format&fit=crop')] bg-no-repeat bg-cover bg-center bg-blend-multiply">
    <div class="px-4 mx-auto max-w-7xl text-center py-24 lg:py-56">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">
            ELITE PROTECTION FOR YOUR SAFETY <br> <span class="text-transparent bg-clip-text bg-linear-to-r from-red-500 to-red-800">CHAOTIC WORLD</span>
        </h1>
        <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">
            Menyediakan solusi keamanan taktis tingkat militer. Dari pengawalan VIP hingga operasi zona konflik. Keamanan Anda adalah misi mutlak kami.
        </p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            <a href="/catalog" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-900">
                LIHAT KATALOG UNIT
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
            <a href="#" class="inline-flex justify-center items-center py-3 px-5 sm:ms-4 text-base font-medium text-center text-white rounded-lg border border-gray-500 hover:bg-gray-700 focus:ring-4 focus:ring-gray-800">
                KONSULTASI
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-900 py-10 px-4">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-white mb-8">WHY CORELOGIC?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="p-6 bg-gray-800 rounded-lg border border-gray-700">
                <div class="text-red-500 text-4xl mb-4">🛡️</div>
                <h3 class="text-xl font-bold text-white mb-2">Zero Compromise</h3>
                <p class="text-gray-400">Tingkat kegagalan 0% dalam 500+ misi pengawalan.</p>
            </div>
            <div class="p-6 bg-gray-800 rounded-lg border border-gray-700">
                <div class="text-red-500 text-4xl mb-4">⚔️</div>
                <h3 class="text-xl font-bold text-white mb-2">Combat Ready</h3>
                <p class="text-gray-400">Personel mantan pasukan khusus (Special Forces).</p>
            </div>
            <div class="p-6 bg-gray-800 rounded-lg border border-gray-700">
                <div class="text-red-500 text-4xl mb-4">🔒</div>
                <h3 class="text-xl font-bold text-white mb-2">Total Privacy</h3>
                <p class="text-gray-400">Identitas klien dijaga dengan protokol enkripsi militer.</p>
            </div>
        </div>
    </div>
</section>
@endsection