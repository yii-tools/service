<?php

declare(strict_types=1);

namespace Forge\Service\Tests\Mailer;

use Forge\Service\Tests\Support;
use Nyholm\Psr7\UploadedFile;
use PHPUnit\Framework\TestCase;

final class MailerTest extends TestCase
{
    use Support\TestTrait;

    protected bool $writeToFiles = true;

    public function testMailer(): void
    {
        $this->createContainer();

        $this->assertTrue(
            $this->mailer
                ->attachments(
                    [
                        [new UploadedFile($this->aliases->get('@resources/data/foo.txt'), 0, UPLOAD_ERR_OK)],
                    ]
                )
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureImage($this->aliases->get('@resources/data/foo.txt'))
                ->signatureText('Signature')
                ->subject('Test subject')
                ->send('test@example.com', ['body' => 'Test body', 'username' => 'Test username'])
        );

        unset($this->aliases, $this->mailer);
    }

    public function testMailerFailer(): void
    {
        $this->writeToFiles = false;

        $this->createContainer();

        $this->assertFalse(
            $this->mailer
                ->from('admin@example.com')
                ->signatureImage($this->aliases->get('@resources/data/foo.txt'))
                ->subject('Test subject')
                ->send('test@example.com', ['body' => 'Test body', 'username' => 'Test username'])
        );

        unset($this->aliases, $this->mailer);
    }
}
