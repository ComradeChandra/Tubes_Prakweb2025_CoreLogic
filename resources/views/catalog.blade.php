@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-12">
    <div class="px-4 mx-auto max-w-7xl">

        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-100 rounded-lg bg-green-800 border border-green-600" role="alert">
                <span class="font-bold">MISSION CONFIRMED!</span> 
                {{ session('success') }}
            </div>
        @endif

        <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-12">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-white">
                TACTICAL UNITS
            </h2>
            <p class="font-light text-gray-400 lg:mb-8 sm:text-xl">
                Pilih unit keamanan sesuai tingkat ancaman yang Anda hadapi.
            </p>
        </div>

        <form action="{{ url('/catalog') }}" method="GET" class="mb-10 p-4 bg-gray-800 rounded-xl border border-gray-700 shadow-lg">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                
                <div class="relative w-full md:w-1/2">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        class="block w-full p-4 ps-10 text-sm text-white border border-gray-600 rounded-lg bg-gray-700 focus:ring-red-500 focus:border-red-500 placeholder-gray-400" 
                        placeholder="Search unit name, code, or type..." 
                        required 
                    />
                    <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                </div>

                <div class="w-full md:w-auto flex items-center gap-2">
                    <label for="category" class="sr-only">Filter Category</label>
                    <select id="category" name="category" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-3.5">
                        <option selected value="">All Categories</option>
                        <option value="combat" {{ request('category') == 'combat' ? 'selected' : '' }}>Combat Units</option>
                        <option value="vip" {{ request('category') == 'vip' ? 'selected' : '' }}>VIP Escort</option>
                        <option value="k9" {{ request('category') == 'k9' ? 'selected' : '' }}>K9 Units</option>
                        <option value="security" {{ request('category') == 'security' ? 'selected' : '' }}>Base Security</option>
                    </select>
                    
                    <a href="{{ url('/catalog') }}" class="p-3.5 text-sm font-medium text-white bg-gray-700 rounded-lg border border-gray-600 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-700">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <div class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-2 lg:grid-cols-4">

            @foreach($services as $service)
            <div class="bg-gray-800 rounded-lg shadow border border-gray-700 hover:border-red-600 transition duration-300 flex flex-col h-full">

                @php
                    $imgUrl = $service->image 
                        ? asset('storage/' . $service->image) 
                        : 'https://via.placeholder.com/400x300?text=No+Image';
                @endphp

                <a href="{{ url('/services/' . $service->id) }}">
                    <img class="w-full h-48 object-cover rounded-t-lg grayscale hover:grayscale-0 transition duration-500" src="{{ $imgUrl }}" alt="{{ $service->name }}">
                </a>

                <div class="p-5 flex flex-col grow">
                    <h3 class="text-xl font-bold tracking-tight text-white mb-2">
                        <a href="{{ url('/services/' . $service->id) }}">{{ $service->name }}</a>
                    </h3>

                    <p class="mb-4 font-light text-gray-400 text-sm">
                        Status: 
                        @php
                            $statusColor = 'text-gray-500';
                            if($service->status === 'available') $statusColor = 'text-green-500';
                            elseif($service->status === 'deployed') $statusColor = 'text-yellow-500';
                        @endphp
                        <span class="{{ $statusColor }} font-bold">{{ strtoupper($service->status) }}</span>
                    </p>

                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-2xl font-bold text-red-500">$ {{ number_format($service->price, 0, ',', '.') }}</span>
                        <a href="{{ url('/services/' . $service->id . '/order') }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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