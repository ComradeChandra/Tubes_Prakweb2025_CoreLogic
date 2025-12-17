@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-12">
    <div class="px-4 mx-auto max-w-7xl">

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
                    $imgUrl = 'https://images.unsplash.com/photo-1542259681-d7039c3dc30e?auto=format&fit=crop&q=80&w=400';
                    if(str_contains($service->name, 'K9')) {
                        $imgUrl = 'https://images.unsplash.com/photo-1558287588-759089726895?auto=format&fit=crop&q=80&w=400';
                    }
                    if(str_contains($service->name, 'VIP')) {
                        $imgUrl = 'https://images.unsplash.com/photo-1551843073-4a9a5b6fcd5f?auto=format&fit=crop&q=80&w=400';
                    }
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
                        <span class="
                            @if($service->status === 'available') text-green-400
                            @elseif($service->status === 'deployed') text-yellow-400
                            @else text-gray-400
                            @endif
                            font-bold
                        ">
                            {{ strtoupper($service->status) }}
                        </span>
                    </p>

                    <!-- HARGA + TOMBOL -->
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-red-500">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
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
