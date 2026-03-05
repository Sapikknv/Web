<?php

namespace App\Http\Controllers;

use App\Models\Destination;

class HomeController extends Controller
{
    public function index()
    {
        $popularDestinations = Destination::with(['category', 'images' => function($q) {
            $q->where('is_primary', true);
        }])
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        $recommendedDestinations = Destination::with(['category', 'images' => function($q) {
            $q->where('is_primary', true);
        }])
            ->orderBy('rating_avg', 'desc')
            ->limit(10)
            ->get();

        $nearbyDestinations = Destination::with(['category', 'images' => function($q) {
            $q->where('is_primary', true);
        }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('home', compact(
            'popularDestinations',
            'recommendedDestinations',
            'nearbyDestinations'
        ));
    }
}
