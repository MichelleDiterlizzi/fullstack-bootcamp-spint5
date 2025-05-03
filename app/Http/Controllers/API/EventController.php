<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
            'price' => 'nullable|numeric',
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
