<?php

declare(strict_types=1);

namespace Yii\Service;

interface ParameterInterface
{
    /**
     * Returns a parameter defined in params.
     *
     * @param string $key The key of the parameter to return.
     * @param mixed $default The default value to return if the parameter does not exist.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;
}
