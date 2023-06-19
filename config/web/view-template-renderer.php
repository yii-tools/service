<?php

declare(strict_types=1);

use Yii\Service\ViewTemplateRenderer;

/** @var array $params */

return [
    ViewTemplateRenderer::class => [
        'class' => ViewTemplateRenderer::class,
        'withLayoutParameters()' => [
            $params['yii-tools/service']['view-template-renderer']['layout-parameters'],
        ],
    ],
];
