<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Parameter;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class ServiceTest extends TestCase
{
    use TestTrait;

    public function testParameterExists(): void
    {
        $this->createContainer();

        $this->assertSame('Yii Demo', $this->parameter->get('app.name'));
    }

    public function testParameterNoExists(): void
    {
        $this->createContainer();

        $this->assertNull($this->parameter->get('app.noExist'));
    }

    public function testParameterNoExistsWithDefaultValue(): void
    {
        $this->createContainer();

        $this->assertEmpty($this->parameter->get('app.noExist', ''));
    }
}
