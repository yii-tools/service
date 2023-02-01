<?php

declare(strict_types=1);

use Yii\Service\MailerService;
use Yii\Service\ParameterService;

return [
    MailerService::class => [
        'class' => MailerService::class,
        'from()' => static fn (ParameterService $parameter) => $parameter->get('app.mailer.from', ''),
        'signatureImage()' => static fn (ParameterService $parameter) => $parameter->get(
            'service.mailer.signatureImage'
        ),
        'signatureText()' => static fn (ParameterService $parameter) => $parameter->get(
            'service.mailer.signatureText'
        ),
        'translatorCategory()' => static fn (ParameterService $parameter) => $parameter->get(
            'service.mailer.translatorCategory'
        ),
        'viewPath()' => static fn (ParameterService $parameter) => $parameter->get(
            'service.mailer.viewPath'
        ),
    ],
];
