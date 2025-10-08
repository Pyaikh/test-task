<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use OpenApi\Annotations as OA;

class TripController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/trips",
     *   summary="Создать тестовую поездку (локально)",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(type="object",
     *       required={"car_id","start_time","end_time"},
     *       @OA\Property(property="car_id", type="integer"),
     *       @OA\Property(property="start_time", type="string", format="date-time"),
     *       @OA\Property(property="end_time", type="string", format="date-time")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $data = $request->validate([
            'car_id' => 'required|integer|exists:cars,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $trip = Trip::create([
            'user_id' => $request->user()->id,
            'car_id' => $data['car_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
        ]);

        return response()->json($trip, 201);
    }
}


