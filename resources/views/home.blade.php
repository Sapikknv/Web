@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-24 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Jelajahi Keindahan Indonesia</h1>
                <p class="text-xl mb-8">Temukan destinasi wisata terbaik untuk liburanmu</p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('destinations.search') }}" method="GET" class="flex">
                        <input type="text" name="q" placeholder="Cari destinasi wisata..."
                            class="flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none">
                        <button type="submit"
                            class="bg-yellow-500 hover:bg-yellow-600 px-6 py-3 rounded-r-lg font-semibold transition">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Tagline -->
                <p class="mt-6 text-sm opacity-75">#JelajahBersamaMyTravel • 100+ Destinasi • 50+ Kota</p>
            </div>
        </div>

        <!-- Destinasi Populer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Destinasi Populer</h2>
                <a href="{{ route('destinations.index', ['sort' => 'popular']) }}"
                    class="text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat Semua →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @forelse($popularDestinations as $destination)
                    @include('components.destination-card', ['destination' => $destination])
                @empty
                    <p class="text-gray-500 col-span-5 text-center py-12">Belum ada destinasi</p>
                @endforelse
            </div>
        </div>

        <!-- Destinasi Rekomendasi -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-white">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Rekomendasi Untukmu</h2>
                <a href="{{ route('destinations.index', ['sort' => 'rating']) }}"
                    class="text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat Semua →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @forelse($recommendedDestinations as $destination)
                    @include('components.destination-card', ['destination' => $destination])
                @empty
                    <p class="text-gray-500 col-span-5 text-center py-12">Belum ada destinasi</p>
                @endforelse
            </div>
        </div>

        <!-- Destinasi Terdekat -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Destinasi Terbaru</h2>
                <a href="{{ route('destinations.index', ['sort' => 'newest']) }}"
                    class="text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat Semua →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @forelse($nearbyDestinations as $destination)
                    @include('components.destination-card', ['destination' => $destination])
                @empty
                    <p class="text-gray-500 col-span-5 text-center py-12">Belum ada destinasi</p>
                @endforelse
            </div>
        </div>

    </div>
    </div>
@endsection