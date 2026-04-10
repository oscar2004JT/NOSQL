<?php

namespace App\Infrastructure;

use App\Contracts\UserRepository;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;
use App\Domain\Entities\UserProfile;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class DynamoDbUserRepository implements UserRepository
{
    public function __construct(
        private DynamoDbClient $client,
        private Marshaler $marshaller,
        private string $tableName,
    ) {
    }

    public function getUserProfile(string $userId): ?UserProfile
    {
        $items = $this->queryUserPartition($userId);
        $profile = collect($items)->firstWhere('Tipo', 'USER');

        if ($profile === null) {
            return null;
        }

        return new UserProfile(
            userId: $userId,
            nombre: $profile['nombre'],
            email: $profile['email'],
            direcciones: $profile['direcciones'] ?? [],
            pagos: $profile['pagos'] ?? [],
        );
    }

    public function getUserOrders(string $userId): array
    {
        return collect($this->queryUserPartition($userId))
            ->where('Tipo', 'ORDER')
            ->map(fn (array $item) => new Order(
                userId: $userId,
                orderId: str_replace('ORDER#', '', $item['SK']),
                estado: $item['estado'],
                fecha: $item['fecha'],
                direccion: $item['direccion'],
                total: $item['total'],
            ))
            ->values()
            ->all();
    }

    public function getOrder(string $userId, string $orderId): ?Order
    {
        $order = collect($this->queryUserPartition($userId))
            ->firstWhere('SK', 'ORDER#'.$orderId);

        if ($order === null) {
            return null;
        }

        return new Order(
            userId: $userId,
            orderId: $orderId,
            estado: $order['estado'],
            fecha: $order['fecha'],
            direccion: $order['direccion'],
            total: $order['total'],
        );
    }

    public function getOrderItems(string $userId, string $orderId): array
    {
        $response = $this->client->query([
            'TableName' => $this->tableName,
            'KeyConditionExpression' => 'PK = :pk AND begins_with(SK, :sk)',
            'ExpressionAttributeValues' => [
                ':pk' => ['S' => 'USER#'.$userId],
                ':sk' => ['S' => 'ORDER#'.$orderId.'#ITEM#'],
            ],
        ])->toArray();

        $items = array_map(
            fn (array $item) => $this->marshaller->unmarshalItem($item),
            $response['Items'] ?? []
        );

        return array_map(function (array $item) use ($userId, $orderId) {
            $parts = explode('#', $item['SK']);

            return new OrderItem(
                userId: $userId,
                orderId: $orderId,
                itemId: (string) end($parts),
                producto: $item['producto'],
                cantidad: $item['cantidad'],
                precio: $item['precio'],
                subtotal: $item['subtotal'],
            );
        }, $items);
    }

    public function createTableIfMissing(): void
    {
        $existingTables = $this->client->listTables()->toArray();
        if (in_array($this->tableName, $existingTables['TableNames'] ?? [], true)) {
            return;
        }

        $this->client->createTable([
            'TableName' => $this->tableName,
            'KeySchema' => [
                ['AttributeName' => 'PK', 'KeyType' => 'HASH'],
                ['AttributeName' => 'SK', 'KeyType' => 'RANGE'],
            ],
            'AttributeDefinitions' => [
                ['AttributeName' => 'PK', 'AttributeType' => 'S'],
                ['AttributeName' => 'SK', 'AttributeType' => 'S'],
            ],
            'BillingMode' => 'PAY_PER_REQUEST',
        ]);
    }

    public function putItem(array $item): void
    {
        $this->client->putItem([
            'TableName' => $this->tableName,
            'Item' => $this->marshaller->marshalItem($item),
        ]);
    }

    private function queryUserPartition(string $userId): array
    {
        $response = $this->client->query([
            'TableName' => $this->tableName,
            'KeyConditionExpression' => 'PK = :pk',
            'ExpressionAttributeValues' => [
                ':pk' => ['S' => 'USER#'.$userId],
            ],
        ])->toArray();

        return array_map(
            fn (array $item) => $this->marshaller->unmarshalItem($item),
            $response['Items'] ?? []
        );
    }
}
