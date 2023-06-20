<?php

declare(strict_types=1);

use Yii\Service\Mailer;
use Yii\Service\MailerInterface;
use Yii\Service\Parameter;
use Yii\Service\ParameterInterface;
use Yii\Service\ViewTemplateRenderer;
use Yiisoft\Config\Config;

/**
 * @var Config $config
 * @var array $params
 */

return [
    MailerInterface::class => [
        'class' => Mailer::class,
        'from()' => [$params['yii-tools/service']['mailer']['from'] ?? ''],
        'signatureImage()' => [$params['yii-tools/service']['mailer']['signature-image'] ?? ''],
        'signatureText()' => [$params['yii-tools/service']['mailer']['signature-text'] ?? ''],
        'translatorCategory()' => [$params['yii-tools/service']['mailer']['translator-category'] ?? 'app'],
        'viewPath()' => [$params['yii-tools/service']['mailer']['view-path'] ?? ''],
    ],
    ParameterInterface::class => [
        'class' => Parameter::class,
        '__construct()' => [$config->get('application-params')],
    ],
    ViewTemplateRenderer::class => [
        'class' => ViewTemplateRenderer::class,
        'withLayoutFile()' => [
            $params['yii-tools/service']['view-template-renderer']['layout-file'] ?? '@layout/main.php',
        ],
        'withLayoutParameters()' => [
            $params['yii-tools/service']['view-template-renderer']['layout-parameters'] ?? [],
        ],
        'withViewPath()' => [
            $params['yii-tools/service']['view-template-renderer']['view-path'] ?? '@views',
        ],
    ],
];
