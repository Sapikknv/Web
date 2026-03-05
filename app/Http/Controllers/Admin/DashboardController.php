<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDestinations = Destination::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();
        $totalViews = Destination::sum('views');

        $popularDestinations = Destination::orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalDestinations',
            'totalCategories',
            'totalUsers',
            'totalViews',
            'popularDestinations'
        ));
    }
}
