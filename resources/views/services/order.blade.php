@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-20 min-h-screen">
    <div class="max-w-3xl mx-auto px-6 text-white text-center">

        <h1 class="text-3xl font-extrabold mb-6">
            Confirm Your Order
        </h1>

        <p class="text-lg mb-4">
            You are about to hire:
        </p>

        <p class="text-2xl font-bold text-red-500 mb-6">
            {{ $service->name }}
        </p>

        <p class="mb-8 text-gray-300">
            Price:
            <span class="font-bold">
                Rp {{ number_format($service->price, 0, ',', '.') }}
            </span>
        </p>

        <div class="flex justify-center gap-4">
            <a href="/catalog"
               class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg">
                Cancel
            </a>

            <button class="bg-red-700 hover:bg-red-800 px-6 py-3 rounded-lg">
                Confirm Hire
            </button>
        </div>

    </div>
</section>
@endsection
