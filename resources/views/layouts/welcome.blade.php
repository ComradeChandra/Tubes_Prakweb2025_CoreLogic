@extends('layouts.app')

@section('content')

<section class="relative bg-gray-900 bg-[url('https://images.unsplash.com/photo-1595590424283-b8f17842773f?q=80&w=2070&auto=format&fit=crop')] bg-no-repeat bg-cover bg-center bg-blend-multiply">
    <div class="absolute inset-0 bg-gradient-to-b from-gray-900/70 via-gray-900/80 to-gray-900"></div>
    
    <div class="relative px-4 mx-auto max-w-7xl text-center py-16 md:py-24 lg:py-48">
        <div class="inline-flex justify-between items-center py-1 px-1 pr-4 mb-7 text-sm rounded-full bg-gray-800 text-white hover:bg-gray-700 border border-gray-600" role="alert">
            <span class="text-xs bg-red-600 rounded-full text-white px-4 py-1.5 mr-3">NEW</span> 
            <span class="text-sm font-medium">Cyber Security Division Available</span> 
            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
        </div>
        <h1 class="mb-4 text-3xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none text-white">
            ELITE PROTECTION FOR A <br> 
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-red-800">CHAOTIC WORLD</span>
        </h1>
        <p class="mb-8 text-base md:text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">
            Menyediakan solusi keamanan taktis tingkat militer. Kami menjamin keselamatan aset dan nyawa Anda di lingkungan paling berbahaya sekalipun.
        </p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            <a href="/catalog" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-900 shadow-lg shadow-red-900/50 transition transform hover:scale-105">
                LIHAT KATALOG UNIT
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
            <a href="#" class="inline-flex justify-center items-center py-3 px-5 sm:ms-4 text-base font-medium text-center text-white rounded-lg border border-gray-500 hover:bg-gray-800 focus:ring-4 focus:ring-gray-700 transition">
                KONSULTASI
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-900 py-12 md:py-16 px-4 border-t border-gray-800">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">WHY CORELOGIC?</h2>
        <p class="text-gray-400 mb-8 md:mb-12 max-w-2xl mx-auto text-sm md:text-base">Standar operasional kami melampaui protokol militer konvensional.</p>
        
        <div class="grid gap-6 md:gap-8 md:grid-cols-3">
            <div class="p-6 md:p-8 bg-gray-800 rounded-xl border border-gray-700 hover:border-red-600 transition duration-300 transform hover:-translate-y-2 group">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-red-900/20 rounded-lg flex items-center justify-center mx-auto mb-4 md:mb-6 group-hover:bg-red-600 transition-colors">
                    <span class="text-2xl md:text-3xl">🛡️</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-white mb-3">Zero Compromise</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Tingkat kegagalan 0% dalam 500+ misi pengawalan tingkat tinggi di 12 negara konflik.</p>
            </div>
            <div class="p-6 md:p-8 bg-gray-800 rounded-xl border border-gray-700 hover:border-red-600 transition duration-300 transform hover:-translate-y-2 group">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-red-900/20 rounded-lg flex items-center justify-center mx-auto mb-4 md:mb-6 group-hover:bg-red-600 transition-colors">
                    <span class="text-2xl md:text-3xl">⚔️</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-white mb-3">Combat Ready</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Seluruh personel adalah veteran pasukan khusus (Kopassus, SAS, Navy SEALs) dengan pengalaman tempur aktif.</p>
            </div>
            <div class="p-6 md:p-8 bg-gray-800 rounded-xl border border-gray-700 hover:border-red-600 transition duration-300 transform hover:-translate-y-2 group">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-red-900/20 rounded-lg flex items-center justify-center mx-auto mb-4 md:mb-6 group-hover:bg-red-600 transition-colors">
                    <span class="text-2xl md:text-3xl">🔒</span>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-white mb-3">Total Privacy</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Identitas klien dijaga ketat. Kami menerima pembayaran via Kripto anonim untuk kerahasiaan total.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-gray-800 py-12 md:py-16 px-4">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-8 md:mb-12 text-center">CLIENT DEBRIEFING</h2>
        
        <div class="grid gap-6 md:gap-8 md:grid-cols-2 lg:grid-cols-3">
            
            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 relative">
                <div class="text-red-600 text-5xl absolute top-4 left-4 opacity-20">"</div>
                <p class="text-gray-300 italic mb-6 relative z-10 pt-4 text-sm">
                    "CoreLogic has been our trusted security partner for over five years. Their team is highly professional, discreet, and always prepared."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-white text-sm">VK</div>
                    <div>
                        <div class="text-white font-bold text-sm">Victor K.</div>
                        <div class="text-red-500 text-xs uppercase">Senior Diplomat</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 relative">
                <div class="text-red-600 text-5xl absolute top-4 left-4 opacity-20">"</div>
                <p class="text-gray-300 italic mb-6 relative z-10 pt-4 text-sm">
                    "Operating in high-risk environments requires a security team you can rely on completely. CoreLogic provides that peace of mind."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-white text-sm">HR</div>
                    <div>
                        <div class="text-white font-bold text-sm">H. Rahman</div>
                        <div class="text-red-500 text-xs uppercase">Director, Apex Energy</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 p-6 rounded-lg border border-gray-700 relative">
                <div class="text-red-600 text-5xl absolute top-4 left-4 opacity-20">"</div>
                <p class="text-gray-300 italic mb-6 relative z-10 pt-4 text-sm">
                    "I value the safety of my family above all else. The CoreLogic team is respectful, unobtrusive, and incredibly effective."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-white text-sm">ST</div>
                    <div>
                        <div class="text-white font-bold text-sm">Sarah T.</div>
                        <div class="text-red-500 text-xs uppercase">Private Investor</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-red-900 py-10 md:py-12 relative overflow-hidden">
    <div class="absolute inset-0 bg-gray-900/50"></div> 
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-4">THREAT LEVEL RISING?</h2>
        <p class="text-gray-200 mb-8 max-w-2xl mx-auto text-base md:text-lg">Jangan menunggu sampai terlambat. Amankan aset dan keselamatan Anda sekarang dengan tim terbaik kami.</p>
        <a href="/catalog" class="text-red-900 bg-white hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-bold rounded-lg text-sm px-8 py-3 focus:outline-none uppercase tracking-wider shadow-lg">
            DEPLOY UNIT NOW
        </a>
    </div>
</section>

@endsection