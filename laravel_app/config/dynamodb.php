<?php

return [
    'table' => env('DYNAMODB_TABLE', 'MiMercado'),
    'endpoint' => env('DYNAMODB_ENDPOINT', 'http://localhost:8000'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'key' => env('AWS_ACCESS_KEY_ID', 'local'),
    'secret' => env('AWS_SECRET_ACCESS_KEY', 'local'),
];
