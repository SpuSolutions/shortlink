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
            'path' => __DIR__ . '/../log/app.log',
        ],

        // Link File Data Access Object
        'linkFileDao' => [
            'upload_path' => __DIR__ . '/../uploads/',
            'totalLinkFiles' => 1000,
        ],
        
        // Link Validator
        'linkValidator' => [
            'reservedWords' => ['about', 'new'],
            'expireTime' => ['min' => 1, 'max' => 60],   // expire time in minutes
            'word' => ['maxLength' => 10]
        ]
    ],
];
