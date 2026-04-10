<?php

namespace App\Domain\Entities;

class OrderItem
{
    public function __construct(
        public string $userId,
        public string $orderId,
        public string $itemId,
        public string $producto,
        public int $cantidad,
        public int $precio,
        public int $subtotal,
    ) {
    }
}
