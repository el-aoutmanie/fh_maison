<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\BookedMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('images')->withCount('bookedMeetings')->latest()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title.en' => 'required|string|max:255',
            'title.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'images.*' => 'nullable|image|max:2048',
            'features.en' => 'nullable|string',
            'features.ar' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'slug' => [
                'en' => Str::slug($request->title['en']),
                'ar' => Str::slug($request->title['ar']),
            ],
            'price' => $request->price,
            'duration' => $request->duration,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle features
        if ($request->features) {
            $data['features'] = [
                'en' => $request->features['en'] ? explode("\n", $request->features['en']) : [],
                'ar' => $request->features['ar'] ? explode("\n", $request->features['ar']) : [],
            ];
        }

        $service = Service::create($data);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('services', 'public');
                $fullUrl = asset('storage/' . $path);
                $service->images()->create([
                    'name' => $imageFile->getClientOriginalName(),
                    'url' => $fullUrl,
                    'alt_text' => $request->title['en'] ?? 'Service image',
                    'order' => $index + 1
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', __('Service created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['images', 'bookedMeetings']);
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $service->load('images');
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title.en' => 'required|string|max:255',
            'title.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'images.*' => 'nullable|image|max:2048',
            'features.en' => 'nullable|string',
            'features.ar' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'slug' => [
                'en' => Str::slug($request->title['en']),
                'ar' => Str::slug($request->title['ar']),
            ],
            'price' => $request->price,
            'duration' => $request->duration,
            'is_active' => $request->is_active ?? true,
        ];

        // Handle features
        if ($request->features) {
            $data['features'] = [
                'en' => $request->features['en'] ? explode("\n", $request->features['en']) : [],
                'ar' => $request->features['ar'] ? explode("\n", $request->features['ar']) : [],
            ];
        }

        $service->update($data);

        // Handle new images (append to existing images)
        if ($request->hasFile('images')) {
            $existingOrder = $service->images()->max('order') ?? 0;
            
            foreach ($request->file('images') as $index => $imageFile) {
                $path = $imageFile->store('services', 'public');
                $fullUrl = asset('storage/' . $path);
                $service->images()->create([
                    'name' => $imageFile->getClientOriginalName(),
                    'url' => $fullUrl,
                    'alt_text' => $request->title['en'] ?? 'Service image',
                    'order' => $existingOrder + $index + 1
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', __('Service updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Delete all associated images
        if ($service->images) {
            foreach ($service->images as $image) {
                $path = str_replace(url('storage/'), '', $image->url);
                Storage::disk('public')->delete($path);
            }
        }
        
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', __('Service deleted successfully'));
    }

    /**
     * Delete a single service image
     */
    public function deleteImage(\App\Models\Image $image)
    {
        // Verify the image belongs to a service
        if (!$image->service_id) {
            return response()->json(['success' => false, 'message' => __('Image not found')], 404);
        }

        // Extract path from full URL (remove domain and /storage/ prefix)
        $path = str_replace(url('storage/'), '', $image->url);
        
        // Delete the file from storage
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Delete the database record
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => __('Image deleted successfully')
        ]);
    }

    /**
     * Display booked meetings
     */
    public function meetings()
    {
        $meetings = BookedMeeting::with('service')->latest()->paginate(15);
        return view('admin.meetings.index', compact('meetings'));
    }

    /**
     * Update meeting status
     */
    public function updateMeetingStatus(Request $request, BookedMeeting $meeting)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $meeting->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('Meeting status updated successfully'));
    }
}
