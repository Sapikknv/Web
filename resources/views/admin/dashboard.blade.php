@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-600 mt-2">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
        
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Destinasi -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-map-marked-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Destinasi</p>
                        <p class="text-2xl font-bold">{{ $totalDestinations }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Kategori -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-tags text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Kategori</p>
                        <p class="text-2xl font-bold">{{ $totalCategories }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Total User -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-users text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total User</p>
                        <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Total Views -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-eye text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Views</p>
                        <p class="text-2xl font-bold">{{ number_format($totalViews) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.destinations.create') }}" 
                       class="bg-blue-600 text-white rounded-lg p-4 text-center hover:bg-blue-700">
                        <i class="fas fa-plus-circle text-2xl mb-2"></i>
                        <p class="text-sm">Tambah Destinasi</p>
                    </a>
                    <a href="{{ route('admin.categories.create') }}" 
                       class="bg-green-600 text-white rounded-lg p-4 text-center hover:bg-green-700">
                        <i class="fas fa-folder-plus text-2xl mb-2"></i>
                        <p class="text-sm">Tambah Kategori</p>
                    </a>
                </div>
            </div>
            
            <!-- Destinasi Populer -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Destinasi Terpopuler</h2>
                <ul class="space-y-3">
                    @foreach($popularDestinations as $dest)
                    <li class="flex justify-between items-center">
                        <span class="text-sm">{{ $dest->name }}</span>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ number_format($dest->views) }} views</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <!-- Menu Navigasi Admin -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Menu Admin</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.destinations.index') }}" 
                   class="border rounded-lg p-4 hover:bg-gray-50 flex items-center">
                    <i class="fas fa-map-marked-alt text-blue-600 text-2xl mr-3"></i>
                    <div>
                        <p class="font-semibold">Kelola Destinasi</p>
                        <p class="text-sm text-gray-500">Tambah, edit, hapus destinasi</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="border rounded-lg p-4 hover:bg-gray-50 flex items-center">
                    <i class="fas fa-tags text-green-600 text-2xl mr-3"></i>
                    <div>
                        <p class="font-semibold">Kelola Kategori</p>
                        <p class="text-sm text-gray-500">Tambah, edit, hapus kategori</p>
                    </div>
                </a>
                
                <a href="#" 
                   class="border rounded-lg p-4 hover:bg-gray-50 flex items-center opacity-50">
                    <i class="fas fa-flag text-red-600 text-2xl mr-3"></i>
                    <div>
                        <p class="font-semibold">Laporan Komentar</p>
                        <p class="text-sm text-gray-500">(Coming Soon)</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection