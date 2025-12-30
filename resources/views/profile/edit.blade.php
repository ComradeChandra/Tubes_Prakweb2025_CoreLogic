@extends('layouts.app')

@section('content')
<!-- 
    CROPPER.JS INTEGRATION
    Library eksternal untuk fitur crop gambar sebelum upload.
    Docs: https://github.com/fengyuanchen/cropperjs
-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

<div class="container mx-auto px-4 py-12 mt-16">
    <div class="max-w-3xl mx-auto">
        
        <!-- 
            HEADER SECTION
            Judul halaman dan deskripsi singkat.
            Menggunakan font-mono untuk kesan teknikal/militer.
        -->
        <div class="mb-8 border-b border-gray-700 pb-4">
            <h1 class="text-3xl font-bold text-white font-mono uppercase tracking-wider">
                <span class="text-red-600">OPERATOR</span> PROFILE
            </h1>
            <p class="text-gray-400 mt-2">Update your personal information and security credentials.</p>
        </div>

        <!-- 
            SUCCESS MESSAGE ALERT
            Muncul setelah user berhasil update profile.
            Dikirim dari ProfileController via ->with('success', ...)
        -->
        @if (session('success'))
            <div class="p-4 mb-6 text-sm text-green-400 rounded-lg bg-gray-800 border border-green-800" role="alert">
                <span class="font-medium">SUCCESS:</span> {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi singkat: Tampilkan notifikasi KTP / lainnya --}}
        @php
            $unread = $user->userNotifications()->where('is_read', false)->count();
            $latestNotifications = $user->userNotifications()->latest()->limit(5)->get();
        @endphp

        @if($unread > 0)
            <div class="p-4 mb-6 text-sm text-yellow-300 rounded-lg bg-gray-800 border border-yellow-900" role="alert">
                <span class="font-medium">NOTICE:</span> You have {{ $unread }} unread notification(s). <a href="#notifications" class="underline">View</a>
            </div>
        @endif

        <div id="notifications" class="mb-6">
            @if($latestNotifications->count())
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-white">Recent Notifications</h3>
                        <form action="{{ route('notifications.markRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded">Mark all as read</button>
                        </form>
                    </div>
                    <ul class="text-gray-300 text-sm list-disc pl-5">
                        @foreach($latestNotifications as $note)
                            <li class="mb-1">
                                <strong class="text-white">{{ $note->title }}:</strong> {{ $note->message }} <span class="text-xs text-gray-400">&middot; {{ $note->created_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- MAIN FORM CONTAINER -->
        <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-xl overflow-hidden">
            <div class="p-6 md:p-8">
                <!-- 
                    FORM UPDATE PROFILE
                    Method: PUT (Sesuai standar RESTful untuk update)
                    Enctype: multipart/form-data (Wajib karena ada upload file)
                -->
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- 
                        SECTION 1: AVATAR & BASIC INFO
                        Layout: Flexbox (Kiri: Avatar, Kanan: Form Input)
                    -->
                    <div class="flex flex-col md:flex-row items-start gap-8 mb-8">
                        
                        <!-- AVATAR UPLOAD AREA -->
                        <div class="flex-shrink-0">
                            <div class="relative group">
                                <!-- Logic: Tampilkan avatar user kalau ada, kalau tidak pakai DiceBear API -->
                                @if($user->avatar)
                                    <img id="avatar-preview" src="{{ Storage::url($user->avatar) }}" alt="Profile Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-gray-700 group-hover:border-red-600 transition duration-300">
                                @else
                                    <img id="avatar-preview" src="https://api.dicebear.com/9.x/initials/svg?seed={{ $user->name }}" alt="Default Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-gray-700 group-hover:border-red-600 transition duration-300">
                                @endif
                                
                                <!-- Overlay Icon Kamera (Muncul saat hover) -->
                                <div class="absolute bottom-0 right-0 bg-red-600 text-white p-2 rounded-full shadow-lg cursor-pointer hover:bg-red-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <!-- Input File Hidden (Di-trigger saat klik area gambar) -->
                                <input type="file" id="avatar-input" name="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Click to change</p>
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 
                            CROPPER MODAL (Hidden by default)
                            Muncul otomatis via JS saat user memilih file gambar.
                        -->
                        <div id="cropper-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-90 flex items-center justify-center">
                            <div class="bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-6 border border-gray-700">
                                <h3 class="text-xl font-bold text-white mb-4 uppercase">Crop Image</h3>
                                <div class="relative h-96 w-full bg-gray-900 rounded-lg overflow-hidden mb-4">
                                    <!-- Image target untuk Cropper.js -->
                                    <img id="cropper-image" src="" alt="To Crop" class="max-w-full h-auto">
                                </div>
                                <div class="flex justify-end gap-4">
                                    <button type="button" id="cancel-crop" class="text-gray-400 hover:text-white border border-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5 uppercase">Cancel</button>
                                    <button type="button" id="crop-button" class="text-white bg-red-700 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 uppercase shadow-lg shadow-red-900/50">Crop & Save</button>
                                </div>
                            </div>
                        </div>

                        <!-- 
                            JAVASCRIPT LOGIC FOR CROPPER
                            1. Listen event 'change' pada input file.
                            2. Load gambar ke modal & inisialisasi Cropper.js.
                            3. Saat tombol 'Crop & Save' diklik:
                               - Ambil hasil crop (Canvas).
                               - Convert ke Blob/File object.
                               - Replace file asli di input form dengan file hasil crop.
                               - Update preview gambar di halaman.
                        -->
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
                        <script>
                            let cropper;
                            const avatarInput = document.getElementById('avatar-input');
                            const cropperModal = document.getElementById('cropper-modal');
                            const cropperImage = document.getElementById('cropper-image');
                            const cropButton = document.getElementById('crop-button');
                            const cancelCrop = document.getElementById('cancel-crop');
                            const avatarPreview = document.getElementById('avatar-preview');

                            // Event: Saat user pilih file
                            avatarInput.addEventListener('change', function(e) {
                                const file = e.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        cropperImage.src = e.target.result;
                                        cropperModal.classList.remove('hidden'); // Tampilkan modal
                                        
                                        // Hapus instance cropper lama jika ada
                                        if (cropper) {
                                            cropper.destroy();
                                        }
                                        
                                        // Inisialisasi Cropper baru
                                        cropper = new Cropper(cropperImage, {
                                            aspectRatio: 1, // Paksa rasio 1:1 (Kotak/Bulat)
                                            viewMode: 1,
                                            dragMode: 'move',
                                            autoCropArea: 1,
                                            background: false,
                                        });
                                    }
                                    reader.readAsDataURL(file);
                                }
                            });

                            // Event: Batal Crop
                            cancelCrop.addEventListener('click', function() {
                                cropperModal.classList.add('hidden');
                                avatarInput.value = ''; // Reset input biar bisa pilih file yang sama lagi
                                if (cropper) {
                                    cropper.destroy();
                                }
                            });

                            // Event: Simpan Hasil Crop
                            cropButton.addEventListener('click', function() {
                                if (cropper) {
                                    cropper.getCroppedCanvas({
                                        width: 400, // Resize hasil crop biar gak terlalu gede
                                        height: 400,
                                    }).toBlob(function(blob) {
                                        // 1. Update Preview di Halaman
                                        const url = URL.createObjectURL(blob);
                                        avatarPreview.src = url;

                                        // 2. Manipulasi Input File (Ganti file asli dengan hasil crop)
                                        const file = new File([blob], "avatar.jpg", { type: "image/jpeg" });
                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(file);
                                        avatarInput.files = dataTransfer.files;

                                        // 3. Tutup Modal
                                        cropperModal.classList.add('hidden');
                                        cropper.destroy();
                                    }, 'image/jpeg');
                                }
                            });
                        </script>

                        <!-- FORM INPUT FIELDS -->
                        <div class="flex-grow w-full space-y-6">
                            <!-- Name Input -->
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Input -->
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIK & Phone -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="nik" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">NIK</label>
                                    <input type="text" id="nik" name="nik" value="{{ old('nik', $user->nik) }}" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                                    @error('nik')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">Phone</label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">Address</label>
                                <textarea id="address" name="address" rows="2" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ID CARD (KTP) Upload -->
                            <!--
                                CATATAN PENGEMBANG:
                                - Bagian ini menampilkan preview KTP jika sudah diupload.
                                - Jika user upload ulang KTP, field `ktp_verified` akan direset ke false.
                                - Verifikasi akhir dilakukan oleh admin melalui halaman admin user detail.
                                - Jika ingin mengganti pesan atau proses, ubah di ProfileController::update.
                            -->
                            <div>
                                <label for="id_card" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">ID Card (KTP)</label>
                                @if($user->id_card_path)
                                    <div class="flex items-center gap-4 mb-2">
                                        <img src="{{ Storage::url($user->id_card_path) }}" alt="KTP" class="w-32 border rounded">
                                        <div>
                                            @if($user->ktp_verified)
                                                <span class="inline-block bg-green-900 text-green-300 text-xs font-bold px-3 py-1 rounded">Terverifikasi</span>
                                            @else
                                                <span class="inline-block bg-yellow-900 text-yellow-300 text-xs font-bold px-3 py-1 rounded">Belum Terverifikasi</span>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-2">Jika KTP salah, upload kembali untuk mengirim permintaan verifikasi ulang.</p>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" id="id_card" name="id_card" accept="image/*" class="block w-full text-sm text-gray-400 bg-gray-900 border border-gray-600 rounded p-2">
                                <p class="mt-1 text-xs text-gray-500">JPG, PNG (Max. 10MB). Disarankan resolusi jelas untuk verifikasi.</p>
                                @error('id_card')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- 
                                MEMBERSHIP TIER BADGE (READ ONLY)
                                Menampilkan status membership user berdasarkan total belanja.
                                Logic tier ada di OrderController & User Model.
                            -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-medium text-gray-300 uppercase tracking-wider">Membership Status</label>
                                    <button type="button" id="view-benefits-btn" class="text-xs text-red-500 hover:text-red-400 underline cursor-pointer transition">
                                        View Benefits
                                    </button>
                                </div>
                                <div class="flex items-center p-3 bg-gray-900 border border-gray-700 rounded-lg">
                                    @if($user->tier === 'elite')
                                        <span class="bg-yellow-900 text-yellow-300 text-xs font-bold px-2.5 py-0.5 rounded border border-yellow-700 uppercase">ELITE TIER</span>
                                        <span class="ml-3 text-sm text-gray-400">20% Discount on all orders</span>
                                    @elseif($user->tier === 'vip')
                                        <span class="bg-purple-900 text-purple-300 text-xs font-bold px-2.5 py-0.5 rounded border border-purple-700 uppercase">VIP TIER</span>
                                        <span class="ml-3 text-sm text-gray-400">10% Discount on all orders</span>
                                    @else
                                        <span class="bg-gray-700 text-gray-300 text-xs font-bold px-2.5 py-0.5 rounded border border-gray-600 uppercase">STANDARD</span>
                                        <span class="ml-3 text-sm text-gray-400">Upgrade to VIP by spending more!</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MEMBERSHIP BENEFITS MODAL -->
                    <div id="benefits-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900 bg-opacity-90 flex items-center justify-center">
                        <div class="bg-gray-800 rounded-lg shadow-xl max-w-lg w-full p-6 border border-gray-700 relative transform transition-all scale-100">
                            <button type="button" id="close-benefits" class="absolute top-4 right-4 text-gray-400 hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            
                            <h3 class="text-xl font-bold text-white mb-6 uppercase text-center border-b border-gray-700 pb-4">
                                <span class="text-red-600">CORELOGIC</span> MEMBERSHIP TIERS
                            </h3>

                            <div class="space-y-4">
                                <!-- Standard -->
                                <div class="p-4 bg-gray-900 rounded-lg border border-gray-700 hover:border-gray-500 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-gray-300 font-bold uppercase tracking-wider">Standard</span>
                                        <span class="text-xs text-gray-500 font-mono">ENTRY LEVEL</span>
                                    </div>
                                    <p class="text-sm text-gray-400">Basic access to all security services. No minimum spending required.</p>
                                </div>

                                <!-- VIP -->
                                <div class="p-4 bg-purple-900/20 rounded-lg border border-purple-700/50 hover:border-purple-500 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-purple-400 font-bold uppercase tracking-wider">VIP Tier</span>
                                        <span class="text-xs text-white bg-purple-700 px-2 py-1 rounded font-mono">10% OFF</span>
                                    </div>
                                    <p class="text-sm text-gray-300">Unlocked after spending <strong class="text-white">$10,000</strong>. Enjoy priority support and 10% discount on all orders.</p>
                                </div>

                                <!-- Elite -->
                                <div class="p-4 bg-yellow-900/20 rounded-lg border border-yellow-700/50 hover:border-yellow-500 transition">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-yellow-400 font-bold uppercase tracking-wider">Elite Tier</span>
                                        <span class="text-xs text-black bg-yellow-500 px-2 py-1 rounded font-mono">20% OFF</span>
                                    </div>
                                    <p class="text-sm text-gray-300">The highest honor. Unlocked after spending <strong class="text-white">$50,000</strong>. Exclusive access to elite units and 20% discount.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        const benefitsModal = document.getElementById('benefits-modal');
                        const viewBenefitsBtn = document.getElementById('view-benefits-btn');
                        const closeBenefitsBtn = document.getElementById('close-benefits');

                        viewBenefitsBtn.addEventListener('click', () => {
                            benefitsModal.classList.remove('hidden');
                        });

                        closeBenefitsBtn.addEventListener('click', () => {
                            benefitsModal.classList.add('hidden');
                        });

                        // Close on click outside
                        benefitsModal.addEventListener('click', (e) => {
                            if (e.target === benefitsModal) {
                                benefitsModal.classList.add('hidden');
                            }
                        });
                    </script>

                    <hr class="border-gray-700 my-8">

                    <!-- 
                        SECTION 2: SECURITY SETTINGS
                        Form ganti password. Kosongkan jika tidak ingin mengganti.
                    -->
                    <h3 class="text-lg font-bold text-white mb-4 uppercase tracking-wider flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Security Settings
                    </h3>
                    <p class="text-sm text-gray-400 mb-6">Leave blank if you don't want to change your password.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">New Password</label>
                            <input type="password" id="password" name="password" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-300 uppercase tracking-wider">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5">
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-end gap-4">
                        <a href="/" class="text-gray-400 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center uppercase tracking-wider transition">
                            Cancel
                        </a>
                        <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-900 font-medium rounded-lg text-sm px-5 py-2.5 text-center uppercase tracking-wider shadow-lg shadow-red-900/50 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection