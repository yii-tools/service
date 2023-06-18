<?php

declare(strict_types=1);

use Yii\Service\Mailer;
use Yiisoft\Mailer\MailerInterface;

/** @var array $params */
return [
    MailerInterface::class => [
        'class' => Mailer::class,
        'from()' => [$params['yii-tools.service.mailer.from']],
        'signatureImage()' => [$params['yii-tools.service.mailer.signature-image']],
        'signatureText()' => [$params['yii-tools.service.mailer.signature-text']],
        'translatorCategory()' => [$params['yii-tools.service.mailer.translator-category']],
        'viewPath()' => [$params['yii-tools.service.mailer.view-path']],
    ],
];
