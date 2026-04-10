<?php

namespace App\Domain\Entities;

class UserProfile
{
    public function __construct(
        public string $userId,
        public string $nombre,
        public string $email,
        public array $direcciones = [],
        public array $pagos = [],
    ) {
    }
}
