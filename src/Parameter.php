<?php

declare(strict_types=1);

namespace Yii\Service;

use Yiisoft\Aliases\Aliases;
use Yiisoft\Arrays\ArrayHelper;

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
    public function __construct(private array $parameters, private Aliases $aliases)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = ArrayHelper::getValueByPath($this->parameters, $key, $default);

        return match (is_string($value)) {
            true => $this->aliases->get($value),
            default => $value,
        };
    }
}
