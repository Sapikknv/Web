@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Explore Destinasi</h1>
                <p class="text-gray-600 mt-2">Temukan destinasi wisata favoritmu</p>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <form action="{{ route('destinations.index') }}" method="GET" class="flex max-w-lg">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari destinasi..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-r-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Filter Kategori (Chip) -->
            <div class="mb-6 overflow-x-auto pb-2">
                <div class="flex space-x-2">
                    <a href="{{ route('destinations.index', array_merge(request()->except('category'), ['category' => null])) }}"
                        class="px-4 py-2 rounded-full whitespace-nowrap transition 
                              {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('destinations.index', array_merge(request()->except('category'), ['category' => $category->slug])) }}"
                            class="px-4 py-2 rounded-full whitespace-nowrap transition 
                                      {{ request('category') == $category->slug ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                            @if($category->icon)
                                <i class="{{ $category->icon }} mr-1"></i>
                            @endif
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Sorting & Info -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <p class="text-gray-600 mb-2 sm:mb-0">
                    Menampilkan {{ $destinations->firstItem() ?? 0 }} - {{ $destinations->lastItem() ?? 0 }}
                    dari {{ $destinations->total() }} destinasi
                </p>

                <div class="flex items-center space-x-2">
                    <label class="text-gray-600">Urutkan:</label>
                    <select onchange="window.location.href = this.value"
                        class="border border-gray-300 rounded-lg px-3 py-1 bg-white">
                        <option
                            value="{{ route('destinations.index', array_merge(request()->except('sort'), ['sort' => 'az'])) }}"
                            {{ request('sort') == 'az' || !request('sort') ? 'selected' : '' }}>
                            A-Z
                        </option>
                        <option
                            value="{{ route('destinations.index', array_merge(request()->except('sort'), ['sort' => 'za'])) }}"
                            {{ request('sort') == 'za' ? 'selected' : '' }}>
                            Z-A
                        </option>
                        <option
                            value="{{ route('destinations.index', array_merge(request()->except('sort'), ['sort' => 'rating'])) }}"
                            {{ request('sort') == 'rating' ? 'selected' : '' }}>
                            Rating Tertinggi
                        </option>
                        <option
                            value="{{ route('destinations.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                            {{ request('sort') == 'popular' ? 'selected' : '' }}>
                            Terpopuler (Views)
                        </option>
                        <option
                            value="{{ route('destinations.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                            {{ request('sort') == 'newest' ? 'selected' : '' }}>
                            Terbaru
                        </option>
                        <option
                            value="{{ route('destinations.index', array_merge(request()->except('sort'), ['sort' => 'nearby', 'lat' => request('lat', session('user_lat')), 'lng' => request('lng', session('user_lng'))])) }}"
                            {{ request('sort') == 'nearby' ? 'selected' : '' }}>
                            Terdekat
                        </option>
                    </select>
                </div>
            </div>

            <!-- Grid Destinasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @forelse($destinations as $destination)
                    @include('components.destination-card', ['destination' => $destination])
                @empty
                    <div class="col-span-5 text-center py-12">
                        <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Belum ada destinasi</p>
                        <p class="text-gray-400">Coba kata kunci lain atau reset filter</p>

                        @if(request('q') || request('category'))
                            <a href="{{ route('destinations.index') }}"
                                class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-times mr-1"></i> Reset Filter
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $destinations->links() }}
            </div>

        </div>
    </div>
@endsection