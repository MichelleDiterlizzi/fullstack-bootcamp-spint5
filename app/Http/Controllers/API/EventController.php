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

    public function index()
    {
        $events = Event::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Events retrieved successfully',
            'data' => $events,
        ], 200);
    }

    private function storeImage(Request $request): ?string{
        return $request->hasFile('image')
            ? $request->file('image')->store('images', 'public')
            : null;
    }  

    private function deleteImage(?string $path): void
    {
        if (is_string($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function validateEventData(Request $request, bool $isUpdate = false, ?Event $event = null)
    {
        $rules = [
            'title' => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'address' => ($isUpdate ? 'nullable' : 'required') . '|string|max:255',
            'event_date' => ($isUpdate ? 'nullable' : 'required') . '|date_format:Y-m-d\TH:i',
            'price' => [
                        'nullable',
                        'required_if:is_free,false',
                        'numeric',
                        function ($attribute, $value, $fail) use ($request, $event) {
                            $isFree = $request->has('is_free')
                                ? filter_var($request->input('is_free'), FILTER_VALIDATE_BOOLEAN)
                                : ($event ? $event->is_free : false);

                            if ($isFree && $value !== null) {
                                $fail('No debes proporcionar un precio si el evento es gratuito.');
                            }

                            if (!$isFree && ($value === null || $value === '')) {
                                $fail('Debes proporcionar un precio cuando el evento no es gratuito.');
                            }
                        },
                    ],
            'is_free' => ($isUpdate ? 'sometimes' : 'required') . '|boolean',
            'description' => ($isUpdate ? 'nullable' : 'required') . '|string|min:50',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
            'category_id' => ($isUpdate ? 'nullable' : 'required') . '|exists:categories,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        return true;
    }
    public function store(Request $request)
    {
        $validation = $this->validateEventData($request);

        if ($validation !== true) {
            return $validation;
        }

        $event = new Event([
            'title' => $request->title,
            'address' => $request->address,
            'event_date' => $request->event_date,
            'price' => $request->boolean('is_free') ? null : $request->price,
            'is_free' => $request->is_free,
            'description' => $request->description,
            'image' => $this->storeImage($request),
            'category_id' => $request->category_id,
        ]);

        $event->creator_id = Auth::id();
        $event->save();

        return response()->json([
            'message' => 'Usuario registrado con éxito!',
            'event' => $event,
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $event = Event::findOrFail($id); 

        return response()->json([
        'event' => $event,
        ], 200);
    }

     public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validation = $this->validateEventData($request, true, $event);

        if ($validation !== true) {
            return $validation;
        }

        $isFree = $request->has('is_free') ? $request->boolean('is_free') : $event->is_free;

        $event->update([
            'title' => $request->input('title', $event->title),
            'address' => $request->input('address', $event->address),
            'event_date' => $request->input('event_date', $event->event_date),
            'price' => $isFree ? null : $request->input('price', $event->price),
            'is_free' => $isFree,
            'description' => $request->input('description', $event->description),
            'category_id' => $request->input('category_id', $event->category_id),
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImage($event->image);
            $event->image = $this->storeImage($request);
            $event->save();
        }

        return response()->json([
            'message' => 'Evento actualizado con éxito!',
            'event' => $event,
        ], 200);
    }

    public function destroy(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $this->deleteImage($event->image);

        $event->delete();

        return response()->json([
            'message' => 'Evento eliminado con éxito!',
        ], 200);
    }
    

    public function attendEvent(Request $request, Event $event, $id_event)
    {
        $event = Event::findOrFail($id_event); // Cargar el modelo explícitamente
    $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No estás autenticado.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'guests_count' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }

        $guestsCount = $request->input('guests_count');

        if ($event->attendees()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Ya estás participando en este evento.'], 409);
        }

        $event->attendees()->attach($user->id, ['guests_count' => $guestsCount]);

        return response()->json(['message' => 'Participación registrada con éxito.', 'event' => $event, 'user' => $user, 'guests_count' => $guestsCount], 201);
    }   

    public function unattendEvent(Request $request, Event $event, $id_event)
    {
        $event = Event::findOrFail($id_event); // Cargar el modelo explícitamente
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No estás autenticado.'], 401);
        }

        if (!$event->attendees()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'No estás participando en este evento.'], 409);
        }

        $event->attendees()->detach($user->id);

        return response()->json(['message' => 'Desasistencia registrada con éxito.'], 200);
    }
}
