<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Parameter;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class Test extends TestCase
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

    public function testParametersWithAliases(): void
    {
        $this->createContainer();

        $this->aliases->set('@root', dirname(__DIR__, 2));

        $this->assertDirectoryExists($this->parameter->get('app.aliases.tests'));
    }

    public function testParameterNoExistsWithDefaultValue(): void
    {
        $this->createContainer();

        $this->assertEmpty($this->parameter->get('app.noExist', ''));
    }
}
