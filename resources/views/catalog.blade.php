// ServiceController.php
public function index() {
    $services = Service::all(); // Mengambil data: Combat Unit, VIP Escort, dll.
    return view('catalog', compact('services'));
}   

@extends('layouts.app')

@section('content')
<section class="bg-gray-900 py-12">
    <div class="px-4 mx-auto max-w-7xl">
        <div class="mx-auto max-w-screen-sm text-center mb-8 lg:mb-16">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-white">TACTICAL UNITS</h2>
            <p class="font-light text-gray-400 lg:mb-16 sm:text-xl">Pilih unit keamanan sesuai tingkat ancaman yang Anda hadapi.</p>
        </div>

        <div class="grid gap-8 mb-6 lg:mb-16 md:grid-cols-2 lg:grid-cols-4">
            
            @foreach($services as $service)
            <div class="items-center bg-gray-800 rounded-lg shadow border border-gray-700 hover:border-red-600 transition duration-300">
                <a href="#">
                    @php
                        $imgUrl = 'https://images.unsplash.com/photo-1542259681-d7039c3dc30e?auto=format&fit=crop&q=80&w=400';
                        if(str_contains($service->name, 'K9')) $imgUrl = 'https://images.unsplash.com/photo-1558287588-759089726895?auto=format&fit=crop&q=80&w=400';
                        if(str_contains($service->name, 'VIP')) $imgUrl = 'https://images.unsplash.com/photo-1551843073-4a9a5b6fcd5f?auto=format&fit=crop&q=80&w=400';
                    @endphp
                    
                    <img class="w-full rounded-t-lg h-48 object-cover grayscale hover:grayscale-0 transition duration-500" src="{{ $imgUrl }}" alt="{{ $service->name }}">
                </a>
                <div class="p-5">
                    <h3 class="text-xl font-bold tracking-tight text-white">
                        <a href="#">{{ $service->name }}</a>
                    </h3>
                    <p class="mt-3 mb-4 font-light text-gray-400 text-sm">
                        Status: <span class="text-green-400 font-bold">AVAILABLE</span>
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-red-500">${{ number_format($service->price, 0, ',', '.') }}</span>
                        <a href="#" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
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