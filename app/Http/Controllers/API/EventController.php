<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Events retrieved successfully',
            'data' => $events,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'event_date' => 'required|date_format:Y-m-d\TH:i',
            'price' => 'required_if:is_free,0|nullable|numeric',
            'is_free' => 'required|boolean',
            'description' => 'required|string|min:50',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $eventData = $request->all();
        if ($eventData['is_free']) {
            $eventData['price'] = null;
        }

        $event = new Event([
            'title' => $request->title,
            'address' => $request->address,
            'event_date' => $request->event_date,
            'price' => $request->price,
            'is_free' => $request->is_free,
            'description' => $request->description,
            'image' => $request->image ? $request->file('image')->store('images') : null,
            'category_id' => $request->category_id,
        ]);

        $event->creator_id = Auth::id();
        $event->save();

        return response()->json([
            'message' => 'Usuario registrado con éxito!',
            'event' => $event,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $event = Event::findOrFail($id); 

        return response()->json([
        'event' => $event,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
        'title' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'event_date' => 'nullable|date_format:Y-m-d\TH:i',
        'price' => 'required_if:is_free,0|nullable|numeric',
        'is_free' => 'nullable|boolean',
        'description' => 'nullable|string|min:50',
        'image' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
        'category_id' => 'nullable|exists:categories,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()->all(),
        ], 422);
    }

    $isFree = filter_var($request->input('is_free', $event->is_free), FILTER_VALIDATE_BOOLEAN);

$updateData = [
    'title' => $request->input('title', $event->title),
    'address' => $request->input('address', $event->address),
    'event_date' => $request->input('event_date', $event->event_date),
    'price' => $isFree ? null : $request->input('price', $event->price),
    'is_free' => $isFree,
    'description' => $request->input('description', $event->description),
    'category_id' => $request->input('category_id', $event->category_id),
];

    $event->update($updateData);

    
    if ($request->hasFile('image')) {
        if ($event->image) {
            Storage::delete($event->image); 
        }
        $event->image = $request->file('image')->store('images');
        $event->save(); 
    }

    return response()->json([
        'message' => 'Evento actualizado con éxito!',
        'event' => $event,
    ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
