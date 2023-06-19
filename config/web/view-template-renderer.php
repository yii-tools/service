<?php

declare(strict_types=1);

use Yii\Service\ViewTemplateRenderer;
use Yiisoft\Config\Config;

/**
 * @var Config $config
 * @var array $params
 */
$params = $config->get('params-web');

return [
    ViewTemplateRenderer::class => [
        'class' => ViewTemplateRenderer::class,
        'withLayoutParameters()' => [
            $params['yii-tools/service']['view-template-renderer']['layout-parameters'],
        ]
    ],
];
