<?php

return [
    'http' => [
        'middlewares' => [
            \Kenzer\Http\Middleware\StartSession::class,
            \Kenzer\Http\Middleware\RecordRequestTime::class,
        ],
    ],
];
