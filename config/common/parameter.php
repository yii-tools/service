<?php

declare(strict_types=1);

use Yii\Service\Parameter;
use Yii\Service\ParameterInterface;
use Yiisoft\Config\Config;

/**
 * @var Config $config
 */
return [
    ParameterInterface::class => [
        'class' => Parameter::class,
        '__construct()' => [$config->get('application-params')],
    ],
];
