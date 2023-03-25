<?php

declare(strict_types=1);

use Yii\Service\Parameter;
use Yiisoft\Config\Config;

/**
 * @var Config $config
 */

return [
    Parameter::class => [
        'class' => Parameter::class,
        '__construct()' => [$config->get('application-params')],
    ],
];
