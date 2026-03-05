@props(['destination'])

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
    <!-- Gambar -->
    <a href="{{ route('destinations.show', $destination->slug) }}" class="block relative h-48 overflow-hidden">
        <img src="{{ $destination->images->first()->image_path ?? 'https://via.placeholder.com/400x300?text=No+Image' }}" 
             alt="{{ $destination->name }}"
             class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
        
        <!-- Kategori Badge -->
        <span class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded-full text-xs z-10">
            {{ $destination->category->name ?? 'Umum' }}
        </span>
        
        <!-- Overlay gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
    </a>
    
    <!-- Konten -->
    <div class="p-4">
        <h3 class="font-bold text-lg mb-1 line-clamp-1">
            <a href="{{ route('destinations.show', $destination->slug) }}" class="hover:text-blue-600">
                {{ $destination->name }}
            </a>
        </h3>
        
        <p class="text-gray-600 text-sm mb-2">
            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
            {{ $destination->short_location }}
        </p>
        
        <p class="text-gray-700 text-sm mb-3 line-clamp-2">
            {{ Str::limit($destination->description, 100) }}
        </p>
        
        <!-- Rating & Stats -->
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($destination->rating_avg))
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="ml-1 font-semibold">{{ number_format($destination->rating_avg, 1) }}</span>
                <span class="text-gray-500 ml-1">({{ $destination->rating_count }})</span>
            </div>
            
            <div class="flex items-center space-x-2 text-gray-500">
                <span title="Views">
                    <i class="fas fa-eye"></i>
                    {{ number_format($destination->views) }}
                </span>
                <span title="Bookmark">
                    <i class="fas fa-bookmark"></i>
                    {{ number_format($destination->bookmarks_count) }}
                </span>
            </div>
        </div>
    </div>
</div>