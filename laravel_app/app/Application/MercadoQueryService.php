<?php

namespace App\Application;

use App\Contracts\UserRepository;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;
use App\Domain\Entities\UserProfile;
use RuntimeException;

class MercadoQueryService
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function getUserData(string $userId): array
    {
        $profile = $this->repository->getUserProfile($userId);
        if ($profile === null) {
            throw new RuntimeException('Usuario no encontrado');
        }

        $orders = $this->repository->getUserOrders($userId);
        $items = [];

        foreach ($orders as $order) {
            $items = [...$items, ...$this->repository->getOrderItems($userId, $order->orderId)];
        }

        return [
            'perfil' => $this->profileToArray($profile),
            'pedidos' => array_map(fn (Order $order) => $this->orderToArray($order), $orders),
            'productos' => array_map(fn (OrderItem $item) => $this->itemToArray($item), $items),
        ];
    }

    public function getOrders(string $userId): array
    {
        $profile = $this->repository->getUserProfile($userId);
        if ($profile === null) {
            throw new RuntimeException('Usuario no encontrado');
        }

        return [
            'pedidos' => array_map(
                fn (Order $order) => $this->orderToArray($order),
                $this->repository->getUserOrders($userId)
            ),
        ];
    }

    public function getOrderDetail(string $userId, string $orderId): array
    {
        $profile = $this->repository->getUserProfile($userId);
        if ($profile === null) {
            throw new RuntimeException('Usuario no encontrado');
        }

        $order = $this->repository->getOrder($userId, $orderId);
        if ($order === null) {
            throw new RuntimeException('Pedido no encontrado');
        }

        return [
            'pedido' => $this->orderToArray($order),
            'items' => array_map(
                fn (OrderItem $item) => $this->itemToArray($item),
                $this->repository->getOrderItems($userId, $orderId)
            ),
        ];
    }

    private function profileToArray(UserProfile $profile): array
    {
        return [
            'user_id' => $profile->userId,
            'nombre' => $profile->nombre,
            'email' => $profile->email,
            'direcciones' => $profile->direcciones,
            'pagos' => $profile->pagos,
        ];
    }

    private function orderToArray(Order $order): array
    {
        return [
            'user_id' => $order->userId,
            'order_id' => $order->orderId,
            'estado' => $order->estado,
            'fecha' => $order->fecha,
            'direccion' => $order->direccion,
            'total' => $order->total,
        ];
    }

    private function itemToArray(OrderItem $item): array
    {
        return [
            'user_id' => $item->userId,
            'order_id' => $item->orderId,
            'item_id' => $item->itemId,
            'producto' => $item->producto,
            'cantidad' => $item->cantidad,
            'precio' => $item->precio,
            'subtotal' => $item->subtotal,
        ];
    }
}
