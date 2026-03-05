<?php

namespace App\Http\Controllers;

use App\Helpers\DistanceHelper;
use App\Models\Category;
use App\Models\Destination;
use App\Models\UserView;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        if ($request->sort === 'nearby' && $request->filled('lat') && $request->filled('lng')) {

            $userLat = $request->lat;
            $userLng = $request->lng;

            $allDestinations = Destination::with(['category', 'images' => function ($q) {
                $q->where('is_primary', true);
            }])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();

            foreach ($allDestinations as $dest) {
                $dest->distance = DistanceHelper::haversine(
                    $userLat, $userLng,
                    $dest->latitude, $dest->longitude
                );
            }

            $allDestinations = $allDestinations->sortBy('distance');

            $page = $request->get('page', 1);
            $perPage = 10;
            $destinations = new \Illuminate\Pagination\LengthAwarePaginator(
                $allDestinations->forPage($page, $perPage),
                $allDestinations->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('destinations.index', compact('destinations', 'categories'));
        }

        $query = Destination::with(['category', 'images' => function ($q) {
            $q->where('is_primary', true);
        }]);

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        switch ($request->sort) {
            case 'az':
                $query->orderBy('name', 'asc');
                break;
            case 'za':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating_avg', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $destinations = $query->paginate(10)->appends($request->query());

        return view('destinations.index', compact('destinations', 'categories'));
    }

    public function show($slug)
    {
        $destination = Destination::with(['category', 'images', 'creator'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = auth()->user();
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        $existingView = UserView::where('destination_id', $destination->id)
            ->when($user, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            }, function ($query) use ($ip) {
                return $query->where('ip_address', $ip);
            })
            ->first();

        if (! $existingView) {
            UserView::create([
                'user_id' => $user->id ?? null,
                'destination_id' => $destination->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);

            $destination->increment('views');
        }

        $comments = $destination->comments()
            ->with(['user', 'replies.user', 'replies.replies'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalComments = $destination->comments()->count();

        return view('destinations.show', compact('destination', 'comments', 'totalComments'));
    }

    public function search(Request $request)
    {
        $q = $request->q;

        return redirect()->route('destinations.index', ['q' => $q]);
    }
}
