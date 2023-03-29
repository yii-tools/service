<?php

declare(strict_types=1);

use Psr\Log\LogLevel;
use Yiisoft\Log\Target\File\FileTarget;

return [
    // Aliases
    'yiisoft/aliases' => [
        'aliases' => [
            '@root' => dirname(__DIR__, 2),
            '@resources' => '@root/data/resources',
            '@runtime' => '@root/data/runtime',
            '@views' => '@resources/mail',
        ],
    ],

    // Log
    'yiisoft/log' => [
        'targets' => [
            FileTarget::class,
        ],
    ],

    'yiisoft/mailer' => [
        'messageBodyTemplate' => [
            'viewPath' => '@views',
        ],
        'fileMailer' => [
            'fileMailerStorage' => '@runtime/mail',
        ],
        'useSendmail' => false,
        'writeToFiles' => true,
    ],

    // Log target file
    'yiisoft/log-target-file' => [
        'fileTarget' => [
            'file' => '@runtime/logs/app.log',
            'levels' => [
                LogLevel::EMERGENCY,
                LogLevel::ERROR,
                LogLevel::WARNING,
                LogLevel::INFO,
                LogLevel::DEBUG,
            ],
            'dirMode' => 0755,
            'fileMode' => null,
        ],
        'fileRotator' => [
            'maxFileSize' => 10240,
            'maxFiles' => 5,
            'fileMode' => null,
            'compressRotatedFiles' => false,
        ],
    ],

    // Translator
    'yiisoft/translator' => [
        'locale' => 'en',
        'fallbackLocale' => 'en',
        'defaultCategory' => 'translator.test',
    ],
];
