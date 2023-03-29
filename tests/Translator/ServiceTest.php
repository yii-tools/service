<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Translator;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class ServiceTest extends TestCase
{
    use TestTrait;

    public function testGetLocale(): void
    {
        $this->createContainer();

        $this->assertSame('en', $this->translator->getLocale());
    }

    public function testTranslate(): void
    {
        $this->createContainer();

        $this->translator->category('app');

        $this->assertSame('app', $this->translator->t('app'));

        $this->translator->category('test');

        $this->assertSame('test', $this->translator->t('test'));
    }
}
