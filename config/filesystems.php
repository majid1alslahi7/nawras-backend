<?php

return [
    'default' => env('FILESYSTEM_DISK', 'public'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path('storage'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'avatars' => [
            'driver' => 'local',
            'root' => public_path('storage/avatars'),
            'url' => env('APP_URL') . '/storage/avatars',
            'visibility' => 'public',
        ],

        'reports' => [
            'driver' => 'local',
            'root' => public_path('storage/reports'),
            'url' => env('APP_URL') . '/storage/reports',
            'visibility' => 'public',
        ],

        'lab-reports' => [
            'driver' => 'local',
            'root' => public_path('storage/lab-reports'),
            'url' => env('APP_URL') . '/storage/lab-reports',
            'visibility' => 'public',
        ],

        'receipts' => [
            'driver' => 'local',
            'root' => public_path('storage/receipts'),
            'url' => env('APP_URL') . '/storage/receipts',
            'visibility' => 'public',
        ],

        'signatures' => [
            'driver' => 'local',
            'root' => public_path('storage/signatures'),
            'url' => env('APP_URL') . '/storage/signatures',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
    ],

    'links' => [],
];
