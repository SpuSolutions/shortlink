<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,

        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' =>dirname( __DIR__) . '/log/app.log',
        ],

        // Link File Data Access Object
        'linkFileDao' => [
            'upload_path' => dirname(__DIR__) . '/uploads/',
            'totalLinkFiles' => 1000,
        ],
        
        // Encryption settings
        'encryption' => [
            'method' => "aes-256-cbc",
            'hash_method' => "sha256",
            'multibyte_key_len' => "8bit",
            'mb_strlen' => "8bit"
        ]
    ],
];
