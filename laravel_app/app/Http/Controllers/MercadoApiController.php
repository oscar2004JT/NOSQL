<?php

namespace App\Http\Controllers;

use App\Application\MercadoQueryService;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class MercadoApiController extends Controller
{
    public function __construct(private MercadoQueryService $service)
    {
    }

    public function user(string $userId): JsonResponse
    {
        try {
            return response()->json($this->service->getUserData($userId));
        } catch (RuntimeException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }

    public function orders(string $userId): JsonResponse
    {
        try {
            return response()->json($this->service->getOrders($userId));
        } catch (RuntimeException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }

    public function orderDetail(string $userId, string $orderId): JsonResponse
    {
        try {
            return response()->json($this->service->getOrderDetail($userId, $orderId));
        } catch (RuntimeException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }
}
