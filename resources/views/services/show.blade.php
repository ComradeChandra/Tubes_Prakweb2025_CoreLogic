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
            Rp {{ number_format($service->price, 0, ',', '.') }}
        </p>

        <p class="text-gray-300 leading-relaxed mb-10">
            {{ $service->description }}
        </p>

        <a href="/catalog"
           class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">
            ← Back to Catalog
        </a>

    </div>
</section>
@endsection
