@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-12">
    <div class="px-4 mx-auto max-w-7xl">

        <!-- ALERT SUCCESS (Added by Chandra) -->
        <!-- Ini bakal muncul kalo ada session 'success' dari OrderController -->
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-100 rounded-lg bg-green-800 border border-green-600" role="alert">
                <span class="font-bold">MISSION CONFIRMED!</span> 
                {{ session('success') }}
            </div>
        @endif

        <!-- JUDUL -->
        <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-white">
                TACTICAL UNITS
            </h2>
            <p class="font-light text-gray-400 lg:mb-16 sm:text-xl">
                Pilih unit keamanan sesuai tingkat ancaman yang Anda hadapi.
            </p>
        </div>

        <!-- GRID -->
        <div class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-2 lg:grid-cols-4">

            @foreach($services as $service)
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700 hover:border-red-600 transition duration-300">

                @php
                    // Logika Gambar:
                    // 1. Cek apakah ada gambar thumbnail di database
                    // 2. Kalau gak ada, pake placeholder default
                    $imgUrl = $service->image 
                        ? asset('storage/' . $service->image) 
                        : 'https://via.placeholder.com/400x300?text=No+Image';
                @endphp

                <!-- GAMBAR -->
                <a href="{{ url('/services/' . $service->id) }}">
                    <img
                        src="{{ $imgUrl }}"
                        alt="{{ $service->name }}"
                        class="w-full h-48 object-cover rounded-t-lg grayscale hover:grayscale-0 transition duration-500"
                    >
                </a>

                <div class="p-5">

                    <!-- NAMA -->
                    <h3 class="text-xl font-bold tracking-tight text-white">
                        <a href="{{ url('/services/' . $service->id) }}">
                            {{ $service->name }}
                        </a>
                    </h3>

                    <!-- STATUS -->
                    <p class="mt-3 mb-4 font-light text-gray-400 text-sm">
                       Status: 
                         @php
                            $statusColor = 'text-gray-500'; // Default warna (misal: maintenance)
                            
                            if($service->status === 'available') {
                                $statusColor = 'text-green-500';
                            } elseif($service->status === 'deployed') {
                                $statusColor = 'text-yellow-500';
                            }
                        @endphp

                        <span class="{{ $statusColor }} font-bold">
                            {{ strtoupper($service->status) }}
                        </span>
                    </p>

                    <!-- HARGA + TOMBOL -->
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-red-500">
                            $ {{ number_format($service->price, 2, '.', ',') }}
                        </span>

                       <a
    href="{{ url('/services/' . $service->id . '/order') }}"
    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
>
    HIRE NOW
</a>

                    </div>

                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>
@endsection
