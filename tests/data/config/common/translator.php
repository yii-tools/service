<?php

declare(strict_types=1);

use Yiisoft\Aliases\Aliases;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\IntlMessageFormatter;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\MessageFormatterInterface;

return [
    // Configure application CategorySource
    'translation.test' => [
        'definition' => static function (Aliases $aliases) {
            return new CategorySource(
                'test',
                new MessageSource($aliases->get('@resources/resources/messages')),
                new IntlMessageFormatter(),
            );
        },
        'tags' => ['translation.categorySource'],
    ],
];
