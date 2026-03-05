@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <div class="mb-4">
            <a href="{{ route('destinations.index') }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Explore
            </a>
        </div>
        
        <!-- Judul & Info -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $destination->name }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 mt-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    <i class="{{ $destination->category->icon ?? 'fas fa-tag' }} mr-1"></i>
                    {{ $destination->category->name }}
                </span>
                
                <span class="text-gray-600">
                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                    {{ $destination->short_location }}
                </span>
                
                <span class="text-gray-600">
                    <i class="fas fa-eye text-gray-400 mr-1"></i>
                    {{ number_format($destination->views) }} dilihat
                </span>
                
                <span class="text-gray-600">
                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                    {{ number_format($destination->rating_avg, 1) }} ({{ $destination->rating_count }} rating)
                </span>
            </div>
        </div>
        
        <!-- Konten Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri (Gambar & Deskripsi) -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($destination->images as $index => $image)
                            <div class="{{ $index === 0 ? 'col-span-4 mb-2' : '' }}">
                                <img src="{{ $image->image_path }}" 
                                     alt="{{ $destination->name }} - Foto {{ $index + 1 }}"
                                     class="w-full h-48 object-cover rounded-lg {{ $index === 0 ? 'h-96' : 'h-24' }} cursor-pointer hover:opacity-90 transition"
                                     onclick="openImageModal('{{ $image->image_path }}')">
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Deskripsi</h2>
                    <div class="text-gray-700 leading-relaxed">
                        {{ $destination->description }}
                    </div>
                </div>
                
                <!-- Google Maps -->
                @if($destination->maps_embed)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold mb-4">Lokasi</h2>
                        <div class="w-full h-96">
                            {!! $destination->maps_embed !!}
                        </div>
                        <p class="text-gray-600 mt-2">
                            <i class="fas fa-map-pin mr-1"></i>
                            {{ $destination->full_location }}
                        </p>
                    </div>
                @endif
            </div>
            
            <!-- Kolom Kanan (Info Tambahan) -->
            <div class="lg:col-span-1">
                <!-- Card Info -->
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                    <h3 class="font-bold text-lg mb-4">Informasi</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Kategori</p>
                            <p class="font-medium">{{ $destination->category->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Lokasi</p>
                            <p class="font-medium">{{ $destination->short_location }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Alamat Lengkap</p>
                            <p class="text-sm">{{ $destination->full_location }}</p>
                        </div>
                        
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2">Statistik</p>
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-lg font-bold">{{ number_format($destination->views) }}</p>
                                    <p class="text-xs text-gray-500">Views</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-lg font-bold">{{ number_format($destination->bookmarks_count) }}</p>
                                    <p class="text-xs text-gray-500">Bookmark</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-lg font-bold">{{ $totalComments ?? 0 }}</p>
                                    <p class="text-xs text-gray-500">Komentar</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-lg font-bold">{{ number_format($destination->rating_avg, 1) }}</p>
                                    <p class="text-xs text-gray-500">Rating</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 space-y-2">
                            @auth
                                <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-bookmark mr-1"></i> Simpan ke Bookmark
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="block text-center w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Login untuk Bookmark
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bagian Komentar (Coming Soon) -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Komentar (Coming Soon)</h2>
            <p class="text-gray-500 text-center py-8">
                <i class="fas fa-comments text-4xl mb-3"></i><br>
                Fitur komentar sedang dalam pengembangan
            </p>
        </div>
        
    </div>
</div>

<!-- Modal untuk Preview Gambar -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center" onclick="closeImageModal()">
    <img id="modalImage" src="" class="max-w-full max-h-full object-contain">
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-4xl">&times;</button>
</div>

@push('scripts')
<script>
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endpush
@endsection