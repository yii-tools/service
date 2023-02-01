<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Mailer;

use HttpSoft\Message\UploadedFile;
use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support;

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
                ->signatureImage('@resources/data/foo.txt')
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
                ->signatureImage('@resources/data/foo.txt')
                ->subject('Test subject')
                ->send('test@example.com', ['body' => 'Test body', 'username' => 'Test username'])
        );

        unset($this->aliases, $this->mailer);
    }
}
