<?php

namespace App\Domain\Entities;

class Order
{
    public function __construct(
        public string $userId,
        public string $orderId,
        public string $estado,
        public string $fecha,
        public string $direccion,
        public int $total,
    ) {
    }
}
