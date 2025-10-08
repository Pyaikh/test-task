<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailableCarsRequest;
use Carbon\Carbon;
use App\Services\CarAvailabilityService;
use OpenApi\Annotations as OA;

class CarController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/cars/available",
     *   summary="Список доступных авто для текущего пользователя",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="start_time", in="query", required=true, @OA\Schema(type="string", format="date-time")),
     *   @OA\Parameter(name="end_time", in="query", required=true, @OA\Schema(type="string", format="date-time")),
     *   @OA\Parameter(name="model_id", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="category_id", in="query", required=false, @OA\Schema(type="integer")),
     *   @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", minimum=1)),
     *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", minimum=1, maximum=100)),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/CarPagination")
     *   )
     * )
     */
    public function available(AvailableCarsRequest $request, CarAvailabilityService $service)
    {
        $user = $request->user();
        if (!$user || !$user->position) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'message' => 'Для пользователя не указана должность или пользователь не аутентифицирован',
                ],
            ]);
        }
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $perPage = (int)($request->input('per_page', 15));

        $paginator = $service->findAvailableCars(
            $user->id,
            $start,
            $end,
            $request->model_id,
            $request->category_id,
            $perPage
        );

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}

