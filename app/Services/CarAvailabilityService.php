<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class CarAvailabilityService
{
    public function findAvailableCars(
        int $userId,
        CarbonInterface $start,
        CarbonInterface $end,
        ?int $modelId,
        ?int $categoryId,
        int $perPage
    ): LengthAwarePaginator {
        $allowedCategoryIds = $this->getAllowedCategoryIdsForUser($userId);

        $query = Car::with(['model.comfortCategory', 'driver'])
            ->whereHas('model', function (Builder $q) use ($allowedCategoryIds) {
                $q->whereIn('comfort_category_id', $allowedCategoryIds);
            })
            ->available($start, $end);

        if (!is_null($modelId)) {
            $query->where('model_id', $modelId);
        }

        if (!is_null($categoryId)) {
            $query->whereHas('model', function (Builder $q) use ($categoryId) {
                $q->where('comfort_category_id', $categoryId);
            });
        }

        return $query->paginate($perPage);
    }

    private function getAllowedCategoryIdsForUser(int $userId)
    {
        /** @var \App\Models\User $user */
        $user = \App\Models\User::with('position.comfortCategories')->find($userId);
        if (!$user || !$user->position) {
            return collect([]);
        }

        return $user->position->comfortCategories->pluck('id');
    }
}


