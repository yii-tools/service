<?php

declare(strict_types=1);

use Yii\Service\MailerService;
use Yii\Service\ParameterService;

return [
    MailerService::class => static function (
        Aliases $aliases,
        LoggerInterface $logger,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        ParameterService $parameter
    ) {
        $mailer = new MailerService($aliases, $logger, $mailer, $translator);

        $mailer->from($parameter->get('yii-tools.service.mailer.from', ''));
        $mailer->signatureImage($parameter->get('yii-tools.service.mailer.signatureImage', ''));
        $mailer->signatureText($parameter->get('yii-tools.service.mailer.signatureText', ''));
        $mailer->translatorCategory($parameter->get('yii-tools.service.mailer.translatorCategory', ''));
        $mailer->viewPath($parameter->get('yii-tools.service.mailer.viewPath', ''));

        return $mailer;
    },
];
