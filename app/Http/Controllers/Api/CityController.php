<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\JsonResponse;

final class CityController extends Controller
{
    public function index(): JsonResponse
    {
        $cities = City::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'slug' => $city->slug,
                    'multiplier' => $city->multiplier,
                ];
            });

        return response()->json($cities);
    }
}