<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoreLogic Security Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;700&display=swap');
        body { font-family: 'Chakra Petch', sans-serif; }
    </style>
</head>

<body class="bg-gray-900 text-gray-200 flex flex-col min-h-screen">

    <nav class="bg-gray-900 border-b border-red-900 fixed w-full z-20 top-0 start-0">
      <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <div class="w-8 h-8 bg-red-600 rounded-sm flex items-center justify-center text-white font-bold">CL</div>
            <span class="self-center text-xl font-semibold whitespace-nowrap text-white">
                CoreLogic <span class="text-red-600 hidden lg:inline">Security Solutions</span>
            </span>
        </a>
        
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse items-center">
            @auth
                <div class="flex items-center gap-4 mr-3 md:mr-0">
                    <div class="hidden md:block text-right mr-3">
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest leading-none mb-1">OPERATOR</div>
                        <div class="text-sm font-bold text-white font-mono uppercase leading-none">{{ Auth::user()->name }}</div>
                    </div>
                    @if(Auth::user()->role !== 'admin')
                        <a href="{{ route('orders.history') }}" class="text-gray-300 hover:text-white border border-gray-600 hover:bg-gray-700 font-medium rounded text-xs px-3 py-2 uppercase">MY ORDERS</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white bg-red-900 border border-red-700 hover:bg-red-800 font-medium rounded text-xs px-3 py-2 uppercase">LOGOUT</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-900 font-medium rounded-lg text-sm px-4 py-2">CLIENT LOGIN</a>
            @endauth
            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-400 rounded-lg md:hidden hover:bg-gray-700 focus:ring-gray-600">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/></svg>
            </button>
        </div>
        
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
          <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-700 rounded-lg bg-gray-800 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-gray-900 items-center">
            <li><a href="/" class="block py-2 px-3 text-white bg-red-700 md:bg-transparent md:text-red-500 md:p-0">HOME</a></li>
            <li><a href="/catalog" class="block py-2 px-3 text-gray-300 hover:bg-gray-700 md:hover:bg-transparent md:hover:text-red-500 md:p-0">CATALOG</a></li>
            <li><a href="#" class="block py-2 px-3 text-gray-300 hover:bg-gray-700 md:hover:bg-transparent md:hover:text-red-500 md:p-0">CONTACT</a></li>
            @auth
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 text-red-500 font-bold border border-red-500 rounded hover:bg-red-900/20 md:border-0 md:hover:text-red-400 md:p-0 uppercase tracking-wider">⚠ ADMIN PANEL</a></li>
                @endif
            @endauth
          </ul>
        </div>
      </div>
    </nav>

    <main class="mt-16 grow">
    @yield('content')
    </main>

    <footer class="bg-gray-900 border-t border-red-900/50 mt-auto">
        <div class="mx-auto w-full max-w-7xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
              <div class="mb-6 md:mb-0 max-w-xs">
                  <a href="/" class="flex items-center mb-4">
                      <div class="w-8 h-8 bg-red-600 rounded-sm flex items-center justify-center text-white font-bold mr-3">CL</div>
                      <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">CoreLogic</span>
                  </a>
                  <p class="text-gray-500 text-sm">
                      Penyedia jasa keamanan profesional terpercaya untuk aset bernilai tinggi, individu VIP, dan pengamanan area khusus.
                  </p>
              </div>
              
              <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                  <div>
                      <h2 class="mb-6 text-sm font-semibold text-white uppercase tracking-wider">Services</h2>
                      <ul class="text-gray-400 font-medium">
                          <li class="mb-4"><a href="/catalog" class="hover:text-red-500 transition">VIP Escort</a></li>
                          <li class="mb-4"><a href="/catalog" class="hover:text-red-500 transition">Base Security</a></li>
                          <li><a href="/catalog" class="hover:text-red-500 transition">K9 Units</a></li>
                      </ul>
                  </div>
                  <div>
                      <h2 class="mb-6 text-sm font-semibold text-white uppercase tracking-wider">Legal</h2>
                      <ul class="text-gray-400 font-medium">
                          <li class="mb-4"><a href="#" class="hover:text-red-500 transition">Privacy Policy</a></li>
                          <li class="mb-4"><a href="#" class="hover:text-red-500 transition">Terms of Engagement</a></li>
                          <li><a href="#" class="hover:text-red-500 transition">Liability Waiver</a></li>
                      </ul>
                  </div>
                  <div>
                      <h2 class="mb-6 text-sm font-semibold text-white uppercase tracking-wider">Secure Contact</h2>
                      <ul class="text-gray-400 font-medium text-sm">
                          <li class="mb-4">Satphone: +88 1600 555 123</li>
                          <li class="mb-4">Encrypted: <span class="font-mono text-red-400">contact@corelogic.sec</span></li>
                          <li>Base: Sector 7, Jakarta ID</li>
                      </ul>
                  </div>
              </div>
            </div>
            <hr class="my-6 border-gray-700 sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center">© 2025 <a href="/" class="hover:underline text-red-500">CoreLogic Security Solutions™</a>. All Rights Reserved.</span>
                <div class="flex mt-4 sm:justify-center sm:mt-0 space-x-4">
                    <span class="text-gray-600 text-xs uppercase tracking-widest border border-gray-600 px-2 py-1 rounded">Secured by 256-bit AES</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
</body>
</html>