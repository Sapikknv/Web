@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Tambah Destinasi Baru</h2>
                    <p class="text-gray-600 mt-1">Isi informasi lengkap tentang destinasi wisata</p>
                </div>
                
                <!-- Form -->
                <form action="{{ route('admin.destinations.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Destinasi -->
                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Destinasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="category_id" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Lokasi Singkat -->
                        <div>
                            <label for="short_location" class="block text-sm font-medium text-gray-700 mb-1">
                                Lokasi Singkat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="short_location" 
                                   id="short_location" 
                                   value="{{ old('short_location') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Contoh: Kuta, Bali"
                                   required>
                            @error('short_location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Lokasi Lengkap -->
                        <div class="col-span-2">
                            <label for="full_location" class="block text-sm font-medium text-gray-700 mb-1">
                                Lokasi Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="full_location" 
                                   id="full_location" 
                                   value="{{ old('full_location') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Contoh: Jl. Pantai Kuta No. 1, Kuta, Badung, Bali"
                                   required>
                            @error('full_location')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Google Maps Embed -->
                        <div class="col-span-2">
                            <label for="maps_embed" class="block text-sm font-medium text-gray-700 mb-1">
                                Google Maps Embed
                            </label>
                            <textarea name="maps_embed" 
                                      id="maps_embed" 
                                      rows="3"
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="&lt;iframe src=&quot;https://www.google.com/maps/embed?pb=...&quot;&gt;&lt;/iframe&gt;">{{ old('maps_embed') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Dapatkan iframe dari Google Maps (Share → Embed Map)
                            </p>
                            @error('maps_embed')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Deskripsi -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6"
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Upload Gambar -->
                        <div class="col-span-2">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">
                                Foto Destinasi <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   name="images[]" 
                                   id="images" 
                                   multiple 
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Bisa pilih multiple file. Maksimal 10 foto, maks 2MB per foto. Format: JPG, JPEG, PNG
                            </p>
                            @error('images')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview Gambar -->
                            <div id="imagePreview" class="grid grid-cols-5 gap-4 mt-4"></div>
                        </div>
                    </div>
                    
                    <!-- Tombol -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                        <a href="{{ route('admin.destinations.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan Destinasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image sebelum upload
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        for (let i = 0; i < files.length; i++) {
            if (i >= 10) break; // Maksimal 10 preview
            
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border">
                    <span class="absolute top-1 right-1 bg-blue-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                        ${i + 1}
                    </span>
                `;
                preview.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection