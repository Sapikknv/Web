<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'rating' => 'required|integer|min:1|max:5'
        ]);
        
        $destination = Destination::find($request->destination_id);
        
        // Cek apakah user sudah pernah rating
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('destination_id', $destination->id)
            ->first();
        
        if ($existingRating) {
            // Update rating yang sudah ada
            $existingRating->update(['rating' => $request->rating]);
            $message = 'Rating berhasil diperbarui';
        } else {
            // Buat rating baru
            Rating::create([
                'user_id' => auth()->id(),
                'destination_id' => $destination->id,
                'rating' => $request->rating
            ]);
            $message = 'Rating berhasil ditambahkan';
        }
        
        // Hitung ulang rata-rata rating
        $avgRating = $destination->ratings()->avg('rating');
        $countRating = $destination->ratings()->count();
        
        $destination->update([
            'rating_avg' => round($avgRating, 1),
            'rating_count' => $countRating
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'avg_rating' => number_format($avgRating, 1),
                'count_rating' => $countRating
            ]);
        }
        
        return back()->with('success', $message);
    }

    /**
     * Get user's rating for a destination
     */
    public function getUserRating(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id'
        ]);
        
        $rating = Rating::where('user_id', auth()->id())
            ->where('destination_id', $request->destination_id)
            ->first();
        
        return response()->json([
            'rating' => $rating ? $rating->rating : 0
        ]);
    }
}
