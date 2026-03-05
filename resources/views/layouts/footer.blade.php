<footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">MyTravel</h3>
                <p class="text-gray-400">Platform wisata terpercaya untuk menjelajahi keindahan Indonesia.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Navigasi</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="hover:text-white">Explore</a></li>
                    @auth
                    <li><a href="{{ route('bookmarks.index') }}" class="hover:text-white">Bookmark</a></li>
                    @endauth
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Kategori</h4>
                <ul class="space-y-2 text-gray-400">
                    @php
                        use App\Models\Category;
                        $footerCategories = Category::limit(3)->get();
                    @endphp
                    @foreach($footerCategories as $category)
                        <li><a href="{{ route('destinations.index', ['category' => $category->slug]) }}" class="hover:text-white">{{ $category->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('destinations.index') }}" class="hover:text-white">Lihat Semua</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Ikuti Kami</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white text-2xl"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-2xl"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-2xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-2xl"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} MyTravel. All rights reserved.</p>
        </div>
    </div>
</footer>