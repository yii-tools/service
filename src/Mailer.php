<?php

declare(strict_types=1);

namespace Yii\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Translator\TranslatorInterface;

use function basename;
use function mime_content_type;

/**
 * Provides a way to send email messages.
 *
 * ```php
 * $mailer
 *     ->attachments(['@resources/attachment/test.txt'])
 *     ->from('admin@example.com')
 *     ->layout(['html' => 'contact'])
 *     ->signatureImage('@resources/attachment/test.txt')
 *     ->signatureText('Signature')
 *     ->subject('Test subject')
 *     ->viewPath('@views')
 *     ->send('test@example.com', ['message' => 'Test body', 'username' => 'Test username']);
 * ```
 */
final class Mailer implements \Yii\Service\MailerInterface
{
    /** @psalm-var string[] */
    private array $attachments = [];
    private string $from = '';
    /** @psalm-var array<string, string>|string|null */
    private array|string|null $layout = ['html' => 'contact'];
    private ?File $signatureImage = null;
    private string $signatureText = '';
    private string $subject = '';
    private string $translatorCategory = 'app';
    private string $viewPath = '';

    public function __construct(
        private Aliases $aliases,
        private LoggerInterface $logger,
        private MailerInterface $mailer,
        private TranslatorInterface $translator
    ) {
    }

    public function attachments(array $value): self
    {
        $new = clone $this;
        $new->attachments = $value;

        return $new;
    }

    public function from(string $value): self
    {
        $new = clone $this;
        $new->from = $value;

        return $new;
    }

    public function layout(array|string|null $value): self
    {
        $new = clone $this;
        $new->layout = $value;

        return $new;
    }

    public function send(string $email, array $params = []): bool
    {
        $message = $this->mailer
            ->compose(
                $this->layout,
                [
                    'translator' => $this->translator,
                    'params' => $params,
                ],
                [
                    'file' => $this->signatureImage,
                    'signatureTextEmail' => $this->signatureText,
                    'translator' => $this->translator,
                ],
            )
            ->withFrom($this->from)
            ->withSubject($this->subject)
            ->withTo($email);

        foreach ($this->attachments as $attachment) {
            $filename = $this->aliases->get($attachment);
            $message = $message->withAttached(
                File::fromPath($filename, basename($filename), mime_content_type($filename))
            );
        }

        return $this->sendInternal($message);
    }

    public function signatureImage(string $value): self
    {
        $new = clone $this;

        if ($value !== '') {
            $value = $this->aliases->get($value);
            $new->signatureImage = File::fromPath($value, basename($value), mime_content_type($value));
        }

        return $new;
    }

    public function signatureText(string $value): self
    {
        $new = clone $this;
        $new->signatureText = $value;

        return $new;
    }

    public function subject(string $value): self
    {
        $new = clone $this;
        $new->subject = $value;

        return $new;
    }

    public function translatorCategory(string $value): self
    {
        $new = clone $this;

        if ($value !== '') {
            $new->translator = $this->translator->withDefaultCategory($value);
        }

        return $new;
    }

    public function viewPath(string $value): self
    {
        $new = clone $this;

        $new->mailer = $new->mailer->withTemplate(
            new MessageBodyTemplate($this->aliases->get($value))
        );

        return $new;
    }

    /**
     * Sends the given email message.
     *
     * @param MessageInterface $message Email message instance to be sent.
     *
     * @throws Exception If sending failed.
     */
    private function sendInternal(MessageInterface $message): bool
    {
        $result = false;

        if ($this->signatureImage !== null) {
            $message = $message->withEmbedded($this->signatureImage);
        }

        try {
            $this->mailer->send($message);

            $result = true;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }
}
