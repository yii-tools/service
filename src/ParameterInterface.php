<?php

declare(strict_types=1);

namespace Yii\Service;

interface ParameterInterface
{
    public function get(string $name, $default = null);
}
