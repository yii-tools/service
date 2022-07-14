<?php

declare(strict_types=1);

namespace Forge\Service;

use Exception;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Mailer\File;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageInterface;
use Yiisoft\Translator\TranslatorInterface;

final class Mailer
{
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
        private LoggerInterface $logger,
        private MailerInterface $mailer,
        private TranslatorInterface $translator
    ) {
    }

    public function attachments(array $values): self
    {
        $new = clone $this;
        $new->attachments = $values;

        return $new;
    }

    public function from(string $value): self
    {
        $new = clone $this;
        $new->from = $value;

        return $new;
    }

    /**
     * @param array $value
     *
     * @psalm-param array<string, string>|string|null $value
     */
    public function layout(array|string|null $value): self
    {
        $new = clone $this;
        $new->layout = $value;

        return $new;
    }

    public function signatureImage(string $value): self
    {
        $new = clone $this;
        $new->signatureImage = File::fromPath($value, basename($value), mime_content_type($value));

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
        $new->translator =  $this->translator->withCategory($value);

        return $new;
    }

    public function viewPath(string $value): self
    {
        $new = clone $this;

        $new->mailer = $new->mailer->withTemplate(
            new MessageBodyTemplate($value)
        );

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

        /** @var array $attachment */
        foreach ($this->attachments as $attachment) {
            /** @var UploadedFileInterface $file */
            foreach ($attachment as $file) {
                if ($file->getError() === UPLOAD_ERR_OK) {
                    $message = $message->withAttached(
                        File::fromContent(
                            (string) $file->getStream(),
                            $file->getClientFilename(),
                            $file->getClientMediaType()
                        )
                    );
                }
            }
        }

        return $this->sendInternal($message);
    }

    private function sendInternal(MessageInterface $message): bool
    {
        $result = false;

        if (null !== $this->signatureImage) {
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
