<?php

namespace App\Contracts;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;
use App\Domain\Entities\UserProfile;

interface UserRepository
{
    public function getUserProfile(string $userId): ?UserProfile;

    public function getUserOrders(string $userId): array;

    public function getOrder(string $userId, string $orderId): ?Order;

    public function getOrderItems(string $userId, string $orderId): array;
}
