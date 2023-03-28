<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Yii\Service\Mailer;
use Yii\Service\ParameterInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Translator\TranslatorInterface;

return [
    Mailer::class => static function (
        Aliases $aliases,
        LoggerInterface $logger,
        MailerInterface $mailer,
        TranslatorInterface $translator,
        ParameterInterface $parameter
    ) {
        $mailer = new Mailer($aliases, $logger, $mailer, $translator);

        return $mailer
            ->from($parameter->get('yii-tools.service.mailer.from', ''))
            ->signatureImage($parameter->get('yii-tools.service.mailer.signature-image', ''))
            ->signatureText($parameter->get('yii-tools.service.mailer.signature-text', ''))
            ->translatorCategory($parameter->get('yii-tools.service.mailer.translator-category', ''))
            ->viewPath($parameter->get('yii-tools.service.mailer.view-path', ''));
    },
];
