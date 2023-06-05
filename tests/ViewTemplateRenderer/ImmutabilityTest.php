<?php

declare(strict_types=1);

namespace Yii\Service\Tests\ViewTemplateRenderer;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class ImmutabilityTest extends TestCase
{
    use TestTrait;

    public function testImmutability(): void
    {
        $viewTemplateRenderer = $this->viewTemplateRenderer;

        $this->assertNotSame($viewTemplateRenderer, $viewTemplateRenderer->withLayoutParameters([]));
        $this->assertNotSame($viewTemplateRenderer, $viewTemplateRenderer->withViewPath(''));
    }
}
