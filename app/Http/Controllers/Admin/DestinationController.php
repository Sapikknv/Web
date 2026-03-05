<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Category;
use App\Models\DestinationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with(['category', 'images' => function($q) {
                $q->where('is_primary', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:destinations',
            'category_id' => 'required|exists:categories,id',
            'short_location' => 'required|string|max:255',
            'full_location' => 'required|string',
            'maps_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'required|string',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $destination = Destination::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'short_location' => $request->short_location,
            'full_location' => $request->full_location,
            'maps_embed' => $request->maps_embed,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->file('images') as $index => $image) {
            $filename = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
            $path = 'destinations/' . $destination->id . '/' . $filename;
            
            Storage::disk('public')->put($path, file_get_contents($image));
            
            DestinationImage::create([
                'destination_id' => $destination->id,
                'image_path' => 'storage/' . $path,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil ditambahkan');
    }

    public function edit(Destination $destination)
    {
        $categories = Category::orderBy('name')->get();
        $destination->load('images');
        
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(Request $request, Destination $destination)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:destinations,name,' . $destination->id,
            'category_id' => 'required|exists:categories,id',
            'short_location' => 'required|string|max:255',
            'full_location' => 'required|string',
            'maps_embed' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'required|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $destination->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'short_location' => $request->short_location,
            'full_location' => $request->full_location,
            'maps_embed' => $request->maps_embed,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            $lastOrder = $destination->images()->max('sort_order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . ($lastOrder + 1 + $index) . '.' . $image->getClientOriginalExtension();
                $path = 'destinations/' . $destination->id . '/' . $filename;
                
                Storage::disk('public')->put($path, file_get_contents($image));
                
                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path' => 'storage/' . $path,
                    'is_primary' => false,
                    'sort_order' => $lastOrder + 1 + $index,
                ]);
            }
        }

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil diperbarui');
    }

    public function destroy(Destination $destination)
    {
        foreach ($destination->images as $image) {
            $path = str_replace('storage/', '', $image->image_path);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $folder = 'destinations/' . $destination->id;
        if (Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->deleteDirectory($folder);
        }
        
        $destination->delete();

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destinasi berhasil dihapus');
    }

    public function deleteImage(Request $request, Destination $destination, DestinationImage $image)
    {
        if ($image->destination_id !== $destination->id) {
            return response()->json(['error' => 'Invalid image'], 403);
        }

        if ($destination->images()->count() <= 1) {
            return response()->json(['error' => 'Destinasi harus memiliki minimal 1 gambar'], 400);
        }

        $path = str_replace('storage/', '', $image->image_path);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        if ($image->is_primary) {
            $newPrimary = $destination->images()->where('id', '!=', $image->id)->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    public function setPrimary(Request $request, Destination $destination, DestinationImage $image)
    {
        if ($image->destination_id !== $destination->id) {
            return response()->json(['error' => 'Invalid image'], 403);
        }

        $destination->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }
}