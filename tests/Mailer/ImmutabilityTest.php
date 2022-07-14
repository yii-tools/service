<?php

declare(strict_types=1);

namespace Forge\Service\Tests\Mailer;

use Forge\Service\Tests\Support;
use PHPUnit\Framework\TestCase;

final class ImmutabilityTest extends TestCase
{
    use Support\TestTrait;

    protected bool $writeToFiles = true;

    public function testImmutability(): void
    {
        $this->createContainer();

        $mailer = $this->mailer;
        $this->assertNotSame($mailer, $mailer->attachments([]));
        $this->assertNotSame($mailer, $mailer->from(''));
        $this->assertNotSame($mailer, $mailer->layout([]));
        $this->assertNotSame($mailer, $mailer->signatureImage($this->aliases->get('@resources/data/foo.txt')));
        $this->assertNotSame($mailer, $mailer->signatureText(''));
        $this->assertNotSame($mailer, $mailer->subject(''));
        $this->assertNotSame($mailer, $mailer->translatorCategory('test'));
        $this->assertNotSame($mailer, $mailer->viewPath(''));
    }
}
