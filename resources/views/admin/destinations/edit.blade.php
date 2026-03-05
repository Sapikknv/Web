@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Edit Destinasi</h2>
                    <p class="text-gray-600 mt-1">Ubah informasi destinasi "{{ $destination->name }}"</p>
                </div>
                
                <!-- Form -->
                <form action="{{ route('admin.destinations.update', $destination) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Destinasi -->
                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Destinasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $destination->name) }}"
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
                                    <option value="{{ $category->id }}" 
                                        {{ (old('category_id', $destination->category_id) == $category->id) ? 'selected' : '' }}>
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
                                   value="{{ old('short_location', $destination->short_location) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                                   value="{{ old('full_location', $destination->full_location) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
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
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('maps_embed', $destination->maps_embed) }}</textarea>
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
                                      required>{{ old('description', $destination->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Gambar Existing -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Saat Ini
                            </label>
                            <div class="grid grid-cols-5 gap-4" id="existingImages">
                                @foreach($destination->images as $image)
                                <div class="relative group" data-image-id="{{ $image->id }}">
                                    <img src="{{ $image->image_path }}" 
                                         class="w-full h-24 object-cover rounded-lg border-2 {{ $image->is_primary ? 'border-blue-600' : 'border-gray-200' }}">
                                    
                                    @if($image->is_primary)
                                    <span class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                        Primary
                                    </span>
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center space-x-2 opacity-0 group-hover:opacity-100">
                                        @if(!$image->is_primary)
                                        <button type="button" 
                                                onclick="setPrimary({{ $image->id }})"
                                                class="bg-green-600 text-white p-1 rounded-full hover:bg-green-700">
                                            <i class="fas fa-star text-xs w-4 h-4"></i>
                                        </button>
                                        @endif
                                        
                                        <button type="button" 
                                                onclick="deleteImage({{ $image->id }})"
                                                class="bg-red-600 text-white p-1 rounded-full hover:bg-red-700">
                                            <i class="fas fa-trash text-xs w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Upload Gambar Baru -->
                        <div class="col-span-2">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">
                                Tambah Foto Baru (Opsional)
                            </label>
                            <input type="file" 
                                   name="images[]" 
                                   id="images" 
                                   multiple 
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Maksimal 10 foto total, maks 2MB per foto
                            </p>
                            
                            <!-- Preview Gambar Baru -->
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
                            Update Destinasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image baru
    document.getElementById('images').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        const totalExisting = {{ $destination->images->count() }};
        
        if (files.length + totalExisting > 10) {
            alert('Total foto tidak boleh lebih dari 10!');
            this.value = '';
            return;
        }
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-green-300">
                    <span class="absolute top-1 right-1 bg-green-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                        ${i + 1}
                    </span>
                `;
                preview.appendChild(div);
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Delete image via AJAX
    function deleteImage(imageId) {
        if (!confirm('Yakin ingin menghapus foto ini?')) return;
        
        fetch(`{{ url('admin/destinations') }}/${destinationId}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Gagal menghapus foto');
            }
        });
    }
    
    // Set primary image
    function setPrimary(imageId) {
        fetch(`{{ url('admin/destinations') }}/${destinationId}/images/${imageId}/primary`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.error || 'Gagal mengatur foto utama');
            }
        });
    }
</script>
@endpush
@endsection