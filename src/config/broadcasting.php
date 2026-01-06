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
    | Supported: "reverb", "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_CONNECTION', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over WebSockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'reverb' => [
            'driver' => 'reverb',
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'app_id' => env('REVERB_APP_ID'),
            'options' => [
                'host' => env('REVERB_HOST'),
                'port' => env('REVERB_PORT', 443),
                'scheme' => env('REVERB_SCHEME', 'https'),
                'useTLS' => env('REVERB_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                // クラスター設定（クライアント側の WebSocket 接続に使用）
                // サーバー側は HTTP API を使用するため、この設定はクライアント側のみに影響
                'cluster' => env('PUSHER_APP_CLUSTER'),
                
                // 【重要】HTTP API エンドポイントに固定
                // Cloudflare 経由かつ外向き通信が HTTP プロキシ必須の環境では、
                // WebSocket 接続が失敗するため、サーバー側は HTTP API 経由で送信する必要がある
                // 'host' を 'api.pusherapp.com' に固定することで、WebSocket 接続を試みず、
                // 確実に HTTP API 経由でイベントを送信する
                'host' => 'api.pusherapp.com',
                
                // HTTPS の標準ポート（HTTP API は常に HTTPS を使用）
                'port' => 443,
                
                // HTTPS プロトコルを使用（HTTP API は常に HTTPS）
                'scheme' => 'https',
                
                // 暗号化を有効化
                'encrypted' => true,
                
                // TLS を使用
                'useTLS' => true,
            ],
            'client_options' => [
                // Guzzle HTTP クライアントのオプション設定
                // Laravel の Pusher ドライバーは内部的に Guzzle を使用して HTTP API 経由で送信する
                // https://docs.guzzlephp.org/en/stable/request-options.html
                
                // 【重要】HTTP プロキシ経由で Pusher API に接続
                // Cloudflare 経由かつ外向き通信が HTTP プロキシ必須の環境では、
                // プロキシ経由で Pusher API に接続する必要がある
                // HTTP_PROXY 環境変数が設定されている場合のみプロキシを使用
                'proxy' => env('HTTP_PROXY'),
                
                // 注意: Guzzle は内部的に curl を使用する場合があるが、
                // プロキシ設定は 'proxy' オプションで行う（curl の CURLOPT_PROXY に相当）
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
