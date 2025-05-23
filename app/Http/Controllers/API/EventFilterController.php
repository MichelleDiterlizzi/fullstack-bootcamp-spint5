<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventFilterController extends Controller
{
    /**
     * Devuelve los eventos más populares ordenados por número de asistentes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popular(Request $request)
    {
        $limit = $request->input('limit', 5);
        $now = Carbon::now();

        $popularEvents = Event::where('event_date', '>', $now)
            ->withCount('attendees')
            ->orderBy('attendees_count', 'desc')
            ->take($limit)
            ->get();

        if ($popularEvents->isEmpty()) {
            return response()->json([
            'message' => 'No hay eventos populares disponibles',
            'data' => [],
        ], 200);
}

        return response()->json([
            'message' => 'Eventos populares obtenidos con éxito',
            'data' => $popularEvents,
        ], 200);
    }

    /**
     * Devuelve los próximos eventos gratuitos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function free(Request $request)
    {
        $limit = $request->input('limit', 5);
        $now = Carbon::now();

        $freeEvents = Event::where('is_free', true)
            ->where('event_date', '>', $now)
            ->orderBy('event_date', 'asc')
            ->take($limit)
            ->get();

        return response()->json([
            'message' => 'Próximos eventos gratuitos obtenidos con éxito',
            'data' => $freeEvents,
        ], 200);
    }

    /**
     * Devuelve los próximos eventos que ocurren antes de una hora específica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function beforeTime(Request $request)
    {
        $limit = $request->input('limit', 5);
        $timeBefore = $request->input('time_before', '19:00:00');
        $now = Carbon::now();

        $dayEvents = Event::where('event_date', '>', $now)
            ->whereTime('event_date', '<', $timeBefore)
            ->orderBy('event_date', 'asc')
            ->take($limit)
            ->get();

        return response()->json([
            'message' => 'Próximos eventos antes de la hora especificada obtenidos con éxito',
            'data' => $dayEvents,
        ], 200);
    }

    /**
     * Devuelve los próximos eventos que ocurren a partir de una hora específica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function afterTime(Request $request)
    {
        $limit = $request->input('limit', 5);
        $timeAfter = $request->input('time_after', '19:00:00');
        $now = Carbon::now();

        $eveningEvents = Event::where('event_date', '>', $now)
            ->whereTime('event_date', '>=', $timeAfter)
            ->orderBy('event_date', 'asc')
            ->take($limit)
            ->get();

        return response()->json([
            'message' => 'Próximos eventos a partir de la hora especificada obtenidos con éxito',
            'data' => $eveningEvents,
        ], 200);
    }
}