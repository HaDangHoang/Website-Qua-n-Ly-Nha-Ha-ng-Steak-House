<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    | Supported: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'pusher'),  // Đảm bảo Pusher là driver mặc định

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),  // Lấy key từ .env
            'secret' => env('PUSHER_APP_SECRET'),  // Lấy secret từ .env
            'app_id' => env('PUSHER_APP_ID'),  // Lấy app_id từ .env
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),  // Lấy cluster từ .env
                'host' => env('PUSHER_HOST') ?: 'api-' . env('PUSHER_APP_CLUSTER', 'mt1') . '.pusher.com',  // Host mặc định nếu không có Pusher_HOST
                'port' => env('PUSHER_PORT', 443),  // Cổng mặc định là 443
                'scheme' => env('PUSHER_SCHEME', 'https'),  // Giao thức https mặc định
                'encrypted' => true,  // Mã hóa kết nối
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',  // Sử dụng TLS nếu sử dụng https
            ],
            'client_options' => [
                // Cấu hình thêm cho Guzzle client nếu cần thiết
            ],
        ],


        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
