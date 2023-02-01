<?php

declare(strict_types=1);

use Yii\Service\Mailer;

/** @var array $params */

return [
    Mailer::class => [
        'class' => Mailer::class,
        'from()' => [$params['forge']['mailer']['from'] ?? ''],
        'signatureImage()' => [$params['forge']['mailer']['signatureImage'] ?? ''],
        'signatureText()' => [$params['forge']['mailer']['signatureText'] ?? ''],
        'translatorCategory()' => [$params['forge']['mailer']['translatorCategory'] ?? ''],
        'viewPath()' => [$params['forge']['mailer']['viewPath'] ?? ''],
    ],
];
