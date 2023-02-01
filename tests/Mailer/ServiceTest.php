<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Mailer;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;
use Yii\Support\Assert;

final class ServiceTest extends TestCase
{
    use TestTrait;

    public function testMailer(): void
    {
        $this->createContainer();

        $this->assertTrue(
            $this->mailer
                ->attachmentsFromPath('@resources/data/test.txt')
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureImage('@resources/data/test.txt')
                ->signatureText('Signature')
                ->subject('Test subject')
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
                ->signatureImage('@resources/data/test.txt')
                ->subject('Test subject')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );

        $this->assertCount(2, Assert::inaccessibleProperty($this->logger, 'messages'));
    }

    public function testMailerWithEmptySignatureImage(): void
    {
        $this->createContainer();

        $this->assertTrue(
            $this->mailer
                ->attachmentsFromPath('@resources/data/test.txt')
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureImage('')
                ->signatureText('Signature')
                ->subject('Test subject')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );
    }

    public function testMailerWithTranslatorCategory(): void
    {
        $this->createContainer();

        $this->assertTrue(
            $this->mailer
                ->attachmentsFromPath('@resources/data/test.txt')
                ->from('admin@example.com')
                ->layout(['html' => 'contact'])
                ->signatureText('Signature')
                ->subject('Test subject')
                ->translatorCategory('')
                ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username'])
        );
    }
}
