<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Yii\Service\MailerService;
use Yii\Service\ParameterService;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Translator\TranslatorInterface;

return [
    MailerService::class => static function (
        Aliases $aliases,
        LoggerInterface $logger,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        ParameterService $parameter
    ) {
        $mailer = new MailerService($aliases, $logger, $mailer, $translator);

        return $mailer
            ->from($parameter->get('yii-tools.service.mailer.from', ''))
            ->signatureImage($parameter->get('yii-tools.service.mailer.signatureImage', ''))
            ->signatureText($parameter->get('yii-tools.service.mailer.signatureText', ''))
            ->translatorCategory($parameter->get('yii-tools.service.mailer.translatorCategory', ''))
            ->viewPath($parameter->get('yii-tools.service.mailer.viewPath', ''));
    },
];
