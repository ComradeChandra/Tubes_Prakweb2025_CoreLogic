@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-16 min-h-screen">
    <div class="max-w-5xl mx-auto px-6 text-white">

        <h1 class="text-4xl font-extrabold mb-4">
            {{ $service->name }}
        </h1>

        <p class="mb-4">
            Status:
            <span class="font-bold text-green-400">
                {{ strtoupper($service->status) }}
            </span>
        </p>

        <p class="text-3xl text-red-500 font-bold mb-6">
            $ {{ number_format($service->price, 2, '.', ',') }}
        </p>

        <p class="text-gray-300 leading-relaxed mb-10">
            {{ $service->description }}
        </p>

        <div class="flex gap-4">
            <a href="/catalog"
               class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
                ← Back to Catalog
            </a>

            <!-- TOMBOL HIRE NOW (Added by Chandra) -->
            <!-- Biar user gak usah balik ke katalog dulu buat nyewa -->
            <a href="{{ url('/services/' . $service->id . '/order') }}"
               class="inline-block bg-red-700 hover:bg-red-800 text-white px-6 py-3 rounded-lg font-bold transition">
                HIRE NOW
            </a>
        </div>

    </div>
</section>
@endsection
