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
                    <span id="avgRating">{{ number_format($destination->rating_avg, 1) }}</span>
                    (<span id="countRating">{{ $destination->rating_count }}</span> rating)
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
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line">
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
                                    <p class="text-lg font-bold">{{ $destination->comments->count() }}</p>
                                    <p class="text-xs text-gray-500">Komentar</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-lg font-bold" id="sidebarAvgRating">{{ number_format($destination->rating_avg, 1) }}</p>
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
        
        <!-- Bagian Rating -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Beri Rating</h2>
            
            @auth
                <div class="flex items-center space-x-1 text-3xl mb-4" id="ratingStars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="far fa-star text-gray-400 cursor-pointer hover:text-yellow-400 transition rating-star" 
                           data-rating="{{ $i }}"
                           onclick="setRating({{ $i }})"></i>
                    @endfor
                </div>
                <p class="text-sm text-gray-500" id="ratingMessage">Klik bintang untuk memberi rating</p>
            @else
                <p class="text-gray-500">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> untuk memberi rating
                </p>
            @endauth
        </div>
        
        <!-- Bagian Komentar -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6" id="comments">
            <h2 class="text-xl font-bold mb-4">Komentar ({{ $destination->comments->count() }})</h2>
            
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                    <textarea name="comment" rows="3" 
                              class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Tulis komentar..." required></textarea>
                    <div class="flex justify-end mt-2">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            @else
                <p class="text-gray-500 mb-6">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> untuk menulis komentar
                </p>
            @endauth
            
            <!-- Daftar Komentar -->
            <div class="space-y-4" id="comments-list">
                @forelse($destination->comments->where('parent_id', null) as $comment)
                    <div class="border-b border-gray-200 pb-4" id="comment-{{ $comment->id }}">
                        <div class="flex items-start">
                            <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name) }}" 
                                 class="w-10 h-10 rounded-full mr-3">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold">{{ $comment->user->name }}</h4>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 mt-1">{{ $comment->comment }}</p>
                                
                                <!-- Like & Reply Buttons -->
                                <div class="flex items-center space-x-4 mt-2 text-sm">
                                    @auth
                                        <button onclick="likeComment({{ $comment->id }})" 
                                                class="text-gray-500 hover:text-blue-600 like-btn" 
                                                data-comment-id="{{ $comment->id }}">
                                            <i class="fa{{ $comment->isLikedBy(auth()->user()) ? 's' : 'r' }} fa-heart mr-1"></i>
                                            <span class="likes-count">{{ $comment->likes_count }}</span>
                                        </button>
                                        <button onclick="showReplyForm({{ $comment->id }})" 
                                                class="text-gray-500 hover:text-blue-600">
                                            <i class="far fa-comment mr-1"></i> Balas
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-600">
                                            <i class="far fa-heart mr-1"></i> {{ $comment->likes_count }}
                                        </a>
                                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-600">
                                            <i class="far fa-comment mr-1"></i> Balas
                                        </a>
                                    @endauth
                                </div>
                                
                                <!-- Reply Form (hidden by default) -->
                                @auth
                                    <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                                        <form action="{{ route('comments.reply', $comment) }}" method="POST">
                                            @csrf
                                            <textarea name="comment" rows="2" 
                                                      class="w-full border border-gray-300 rounded-lg p-2 text-sm"
                                                      placeholder="Tulis balasan..." required></textarea>
                                            <div class="flex justify-end space-x-2 mt-2">
                                                <button type="button" 
                                                        onclick="hideReplyForm({{ $comment->id }})"
                                                        class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                                                    Batal
                                                </button>
                                                <button type="submit" 
                                                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                    Kirim Balasan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endauth
                                
                                <!-- Replies -->
                                @if($comment->replies->count() > 0)
                                    <div class="ml-8 mt-4 space-y-3">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex items-start" id="comment-{{ $reply->id }}">
                                                <img src="{{ $reply->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name) }}" 
                                                     class="w-8 h-8 rounded-full mr-2">
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between">
                                                        <h5 class="font-semibold text-sm">{{ $reply->user->name }}</h5>
                                                        <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-gray-700 text-sm">{{ $reply->comment }}</p>
                                                    
                                                    <!-- Like for replies -->
                                                    @auth
                                                        <div class="mt-1">
                                                            <button onclick="likeComment({{ $reply->id }})" 
                                                                    class="text-xs text-gray-500 hover:text-blue-600 like-btn"
                                                                    data-comment-id="{{ $reply->id }}">
                                                                <i class="fa{{ $reply->isLikedBy(auth()->user()) ? 's' : 'r' }} fa-heart mr-1"></i>
                                                                <span class="likes-count">{{ $reply->likes_count }}</span>
                                                            </button>
                                                        </div>
                                                    @endauth
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada komentar. Jadilah yang pertama!</p>
                @endforelse
            </div>
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
    // Image Modal
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // ===== RATING =====
    // Load user's existing rating
    @auth
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ route("ratings.user") }}?destination_id={{ $destination->id }}')
            .then(res => res.json())
            .then(data => {
                if (data.rating > 0) {
                    highlightStars(data.rating);
                }
            });
    });
    @endauth
    
    function highlightStars(rating) {
        const stars = document.querySelectorAll('#ratingStars i');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far', 'text-gray-400');
                star.classList.add('fas', 'text-yellow-400');
            } else {
                star.classList.remove('fas', 'text-yellow-400');
                star.classList.add('far', 'text-gray-400');
            }
        });
    }
    
    function setRating(rating) {
        highlightStars(rating);
        
        document.getElementById('ratingMessage').innerHTML = 'Menyimpan rating...';
        
        fetch('{{ route("ratings.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                destination_id: {{ $destination->id }},
                rating: rating
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('ratingMessage').innerHTML = data.message;
                // Update rating display
                document.getElementById('avgRating').innerText = data.avg_rating;
                document.getElementById('countRating').innerText = data.count_rating;
                document.getElementById('sidebarAvgRating').innerText = data.avg_rating;
            } else {
                document.getElementById('ratingMessage').innerHTML = 'Gagal menyimpan rating';
            }
        })
        .catch(err => {
            document.getElementById('ratingMessage').innerHTML = 'Terjadi kesalahan';
        });
    }
    
    // ===== KOMENTAR =====
    function likeComment(commentId) {
        fetch(`/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const btn = document.querySelector(`.like-btn[data-comment-id="${commentId}"]`);
                const icon = btn.querySelector('i');
                const countSpan = btn.querySelector('.likes-count');
                
                if (data.liked) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
                
                countSpan.innerText = data.likes_count;
            }
        });
    }
    
    function showReplyForm(commentId) {
        document.getElementById(`reply-form-${commentId}`).classList.remove('hidden');
    }
    
    function hideReplyForm(commentId) {
        document.getElementById(`reply-form-${commentId}`).classList.add('hidden');
    }
    
    // Scroll to comment if hash exists
    if (window.location.hash) {
        const element = document.querySelector(window.location.hash);
        if (element) {
            setTimeout(() => {
                element.scrollIntoView({ behavior: 'smooth' });
                element.style.backgroundColor = '#fef3c7';
                setTimeout(() => {
                    element.style.backgroundColor = '';
                }, 2000);
            }, 500);
        }
    }
</script>
@endpush
@endsection