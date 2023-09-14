<?php

declare(strict_types=1);

namespace Yii\Service;

use RuntimeException;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Arrays\ArrayHelper;

use function is_array;
use function is_bool;
use function is_string;

/**
 * Parameter provides a way to get application Parameter defined in config/parameters.php.
 *
 * In order to use in a handler or any other place supporting auto-wired injection:
 *
 * ```php
 * $parameters = [
 *     'admin' => [
 *         'email' => 'demo@example.com'
 *     ],
 * ];
 * ```
 *
 * ```php
 * public function actionIndex(ParameterService $parameterService)
 * {
 *     $adminEmail = $parameterService->get('admin.email', 'admin@example.com');
 *     // return demo@example.com or admin@example.com if search key not exists in Parameter.
 * }
 * ```
 */
final class Parameter implements ParameterInterface
{
    public function __construct(private array $parameters, private readonly Aliases $aliases)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        /** @var mixed $value */
        $value = ArrayHelper::getValueByPath($this->parameters, $key, $default);

        if (is_string($value)) {
            $value = $this->aliases->get($value);
        }

        return $value;
    }

    public function getCastString(string $key, mixed $default = null): string
    {
        /** @psalm-var mixed $value */
        $value = $this->get($key, $default);

        if (is_array($value)) {
            throw new RuntimeException(
                'Unable to cast array to string. Please use `get()` method instead of `getCastString()`.'
            );
        }

        return match (true) {
            is_bool($value) => $value ? 'true' : 'false',
            default => (string) $value,
        };
    }

    public function set(string $key, mixed $value): void
    {
        ArrayHelper::setValueByPath($this->parameters, $key, $value);
    }

    public function add(string $key, mixed $value): void
    {
        $currentValue = $this->get($key, []);

        if (is_array($currentValue) === false) {
            throw new RuntimeException('Unable to add value to non-array parameter.');
        }

        $currentValue[] = $value;

        $this->set($key, $currentValue);
    }
}
