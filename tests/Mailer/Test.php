<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Mailer;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;
use Yii\Support\Assert;

final class Test extends TestCase
{
    use TestTrait;

    public function testMailer(): void
    {
        $this->assertTrue(
            $this->mailer
                ->attachments(['@resources/attachment/test.txt'])
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureImage('@resources/attachment/test.txt')
                ->signatureText('Signature')
                ->subject('Test subject')
                ->viewPath('@views')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );
    }

    public function testMailerFailed(): void
    {
        $this->writeToFiles = false;

        $this->createContainer();

        $this->assertFalse(
            $this->mailer
                ->from('admin@example.com')
                ->signatureImage('@resources/attachment/test.txt')
                ->subject('Test subject')
                ->viewPath('@views')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );

        $this->assertCount(2, Assert::inaccessibleProperty($this->logger, 'messages'));
    }

    public function testMailerWithEmptySignatureImage(): void
    {
        $this->assertTrue(
            $this->mailer
                ->attachments(['@resources/attachment/test.txt'])
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureImage('')
                ->signatureText('Signature')
                ->subject('Test subject')
                ->viewPath('@views')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );
    }

    public function testMailerWithTranslatorCategory(): void
    {
        $this->assertTrue(
            $this->mailer
                ->attachments(['@resources/attachment/test.txt'])
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureText('Signature')
                ->subject('Test subject')
                ->translatorCategory('')
                ->viewPath('@views')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );
    }
}
