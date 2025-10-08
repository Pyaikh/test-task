<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Corporate Cars API",
 *   description="API для получения доступных служебных автомобилей"
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="Local"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 *
 * @OA\Schema(
 *   schema="CarModel",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="comfort_category_id", type="integer"),
 *   @OA\Property(property="comfort_category", ref="#/components/schemas/ComfortCategory")
 * )
 *
 * @OA\Schema(
 *   schema="Driver",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="name", type="string")
 * )
 *
 * @OA\Schema(
 *   schema="ComfortCategory",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="name", type="string")
 * )
 *
 * @OA\Schema(
 *   schema="Car",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="model_id", type="integer"),
 *   @OA\Property(property="driver_id", type="integer"),
 *   @OA\Property(property="license_plate", type="string"),
 *   @OA\Property(property="model", ref="#/components/schemas/CarModel"),
 *   @OA\Property(property="driver", ref="#/components/schemas/Driver")
 * )
 *
 * @OA\Schema(
 *   schema="PaginationMeta",
 *   type="object",
 *   @OA\Property(property="current_page", type="integer"),
 *   @OA\Property(property="per_page", type="integer"),
 *   @OA\Property(property="total", type="integer"),
 *   @OA\Property(property="last_page", type="integer")
 * )
 *
 * @OA\Schema(
 *   schema="CarPagination",
 *   type="object",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")),
 *   @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 * )
 *
 * @OA\Schema(
 *   schema="Trip",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="user_id", type="integer"),
 *   @OA\Property(property="car_id", type="integer"),
 *   @OA\Property(property="start_time", type="string", format="date-time"),
 *   @OA\Property(property="end_time", type="string", format="date-time")
 * )
 */
abstract class Controller
{
    //
}
