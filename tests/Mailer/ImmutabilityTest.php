<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Mailer;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class ImmutabilityTest extends TestCase
{
    use TestTrait;

    public function testImmutability(): void
    {
        $this->createContainer();

        $mailer = $this->mailer;

        $this->assertNotSame($mailer, $mailer->attachments([]));
        $this->assertNotSame($mailer, $mailer->from(''));
        $this->assertNotSame($mailer, $mailer->layout([]));
        $this->assertNotSame($mailer, $mailer->signatureImage('@resources/data/test.txt'));
        $this->assertNotSame($mailer, $mailer->signatureText(''));
        $this->assertNotSame($mailer, $mailer->subject(''));
        $this->assertNotSame($mailer, $mailer->translatorCategory('test'));
        $this->assertNotSame($mailer, $mailer->viewPath(''));
    }
}
