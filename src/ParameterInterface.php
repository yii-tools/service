<?php

declare(strict_types=1);

namespace Yii\Service;

/**
 * Provides a way to get application Parameter defined in `config/application-params.php`.
 */
interface ParameterInterface
{
    /**
     * Adds a parameter to params.
     *
     * @param string $key The key of the parameter to add.
     * @param mixed $value The value of the parameter to add.
     */
    public function add(string $key, mixed $value): void;

    /**
     * Returns a parameter defined in params.
     *
     * @param string $key The key of the parameter to return.
     * @param mixed $default The default value to return if the parameter does not exist.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Returns a parameter defined in params as string.
     *
     * @param string $key The key of the parameter to return.
     * @param mixed $default The default value to return if the parameter does not exist.
     */
    public function getCastString(string $key, mixed $default = null): string;

    /**
     * Sets a parameter defined in params.
     *
     * @param string $key The key of the parameter to set.
     * @param mixed $value The value of the parameter to set.
     */
    public function set(string $key, mixed $value): void;
}
