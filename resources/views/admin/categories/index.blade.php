@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Kelola Destinasi</h2>
                    <a href="{{ route('admin.destinations.create') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i> Tambah Destinasi
                    </a>
                </div>
                
                <!-- Alert Success -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($destinations as $index => $destination)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $destinations->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($destination->images->first())
                                        <img src="{{ $destination->images->first()->image_path }}" 
                                             alt="{{ $destination->name }}"
                                             class="w-20 h-16 object-cover rounded">
                                    @else
                                        <div class="w-20 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $destination->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                        {{ $destination->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $destination->short_location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($destination->views) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {{ number_format($destination->rating_avg, 1) }}
                                    ({{ $destination->rating_count }})
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.destinations.destroy', $destination) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus destinasi ini? Semua data terkait akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-map-marked-alt text-4xl mb-3"></i>
                                    <p>Belum ada destinasi</p>
                                    <a href="{{ route('admin.destinations.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                        Tambah destinasi sekarang
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $destinations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection