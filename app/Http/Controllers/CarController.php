<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Car;
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
    public function available(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'model_id' => 'nullable|integer|exists:car_models,id',
            'category_id' => 'nullable|integer|exists:comfort_categories,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

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

        $allowedCategories = $user->position
            ->comfortCategories()
            ->pluck('id');

        $query = Car::with(['model.comfortCategory', 'driver'])
            ->whereHas('model', function ($q) use ($allowedCategories) {
                $q->whereIn('comfort_category_id', $allowedCategories);
            })
            ->available($start, $end);

        if ($request->filled('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('model', function ($q) use ($request) {
                $q->where('comfort_category_id', $request->category_id);
            });
        }

        $perPage = (int)($request->input('per_page', 15));
        $paginator = $query->paginate($perPage);

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

