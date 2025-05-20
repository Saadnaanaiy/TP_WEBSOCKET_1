<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard Settings
    |--------------------------------------------------------------------------
    |
    | You can configure the dashboard settings from here.
    |
    */

    'dashboard' => [
        'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
        'domain' => env('LARAVEL_WEBSOCKETS_DOMAIN'),
        'path' => env('LARAVEL_WEBSOCKETS_PATH', 'laravel-websockets'),
        'middleware' => [
            'web',
            \BeyondCode\LaravelWebSockets\Dashboard\Http\Middleware\Authorize::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    |
    | Here you can define the clients that should be able to connect to the
    | WebSocket server.
    |
    */

    'clients' => [
        [
            'id' => env('PUSHER_APP_ID', 'local-app-id'),
            'name' => env('APP_NAME', 'Laravel'),
            'key' => env('PUSHER_APP_KEY', 'local-key'),
            'secret' => env('PUSHER_APP_SECRET', 'local-secret'),
            'enable_client_messages' => false,
            'enable_statistics' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting Replication PubSub
    |--------------------------------------------------------------------------
    |
    | You can enable replication for your broadcast events to ensure that they
    | are received by all WebSocket servers in a cluster.
    |
    */

    'replication' => [
        'mode' => env('WEBSOCKETS_REPLICATION_MODE', 'local'),
        'modes' => [
            'local' => [
                'driver' => 'local',
                'channel' => 'websockets',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum Request Size
    |--------------------------------------------------------------------------
    |
    | The maximum request size in kilobytes that is allowed for an incoming
    | WebSocket request.
    |
    */

    'max_request_size_in_kb' => 250,

    /*
    |--------------------------------------------------------------------------
    | SSL Configuration
    |--------------------------------------------------------------------------
    |
    | By default, the configuration allows only secure WebSocket connections.
    | If you want to allow insecure connections, set 'verify_peer' to false.
    |
    */

    'ssl' => [
        'local_cert' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_CERT', null),
        'capath' => env('LARAVEL_WEBSOCKETS_SSL_CA', null),
        'local_pk' => env('LARAVEL_WEBSOCKETS_SSL_LOCAL_PK', null),
        'passphrase' => env('LARAVEL_WEBSOCKETS_SSL_PASSPHRASE', null),
        'verify_peer' => env('APP_ENV') === 'production',
        'allow_self_signed' => env('APP_ENV') !== 'production',
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Handlers
    |--------------------------------------------------------------------------
    |
    | Here you can specify the route handlers that will take care of incoming
    | WebSocket connections and messaging.
    |
    */

    'handlers' => [
        'websocket' => \BeyondCode\LaravelWebSockets\Server\WebSocketHandler::class,
        'health' => \BeyondCode\LaravelWebSockets\Server\HealthHandler::class,
        'trigger_event' => \BeyondCode\LaravelWebSockets\API\TriggerEvent::class,
        'fetch_channels' => \BeyondCode\LaravelWebSockets\API\FetchChannels::class,
        'fetch_channel' => \BeyondCode\LaravelWebSockets\API\FetchChannel::class,
        'fetch_users' => \BeyondCode\LaravelWebSockets\API\FetchUsers::class,
    ],

];
