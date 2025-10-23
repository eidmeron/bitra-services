<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

final class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::where('status', 'active')
            ->with('category')
            ->orderBy('name')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'slug' => $service->slug,
                    'description' => $service->description,
                    'category_name' => $service->category->name ?? '',
                    'base_price' => $service->base_price,
                ];
            });

        return response()->json($services);
    }
}