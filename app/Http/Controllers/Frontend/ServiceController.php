<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\BookedMeeting;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('images')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return view('frontend.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        if (!$service->is_active) {
            abort(404);
        }

        $service->load('images');

        return view('frontend.services.show', compact('service'));
    }

    public function book(Request $request, Service $service)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'message' => 'nullable|string|max:1000',
        ]);

        $validated['service_id'] = $service->id;
        $validated['status'] = 'pending';

        BookedMeeting::create($validated);

        return back()->with('success', __('Your booking request has been submitted successfully. We will contact you soon.'));
    }
}
