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

final class MailerService
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

    /**
     * Returns a new instance with the specified attachments.
     *
     * @param array $value Attachments.
     *
     * @psalm-param string[] $value
     */
    public function attachments(array $value): self
    {
        $new = clone $this;
        $new->attachments = $value;

        return $new;
    }

    /**
     * Returns a new instance with the specified from.
     *
     * @param string $value From.
     */
    public function from(string $value): self
    {
        $new = clone $this;
        $new->from = $value;

        return $new;
    }

    /**
     * Returns a new instance with the specified layout.
     *
     * @param array $value Layout.
     *
     * @psalm-param array<string, string>|string|null $value
     */
    public function layout(array|string|null $value): self
    {
        $new = clone $this;
        $new->layout = $value;

        return $new;
    }

    /**
     * Returns a new instance with the specified signature image.
     *
     * @param string $value Signature image.
     */
    public function signatureImage(string $value): self
    {
        $new = clone $this;

        if ($value !== '') {
            $value = $this->aliases->get($value);
            $new->signatureImage = File::fromPath($value, basename($value), mime_content_type($value));
        }

        return $new;
    }

    /**
     * Returns a new instance with the specified signature text.
     *
     * @param string $value Signature text.
     */
    public function signatureText(string $value): self
    {
        $new = clone $this;
        $new->signatureText = $value;

        return $new;
    }

    /**
     * Returns a new instance with the specified subject.
     *
     * @param string $value Subject.
     */
    public function subject(string $value): self
    {
        $new = clone $this;
        $new->subject = $value;

        return $new;
    }

    /**
     * Returns a new instance with the specified translator category.
     *
     * @param string $value Translator category.
     */
    public function translatorCategory(string $value): self
    {
        $new = clone $this;

        if ($value !== '') {
            $new->translator = $this->translator->withDefaultCategory($value);
        }

        return $new;
    }

    /**
     * Returns a new instance with the specified view path.
     *
     * @param string $value View path.
     */
    public function viewPath(string $value): self
    {
        $new = clone $this;

        $new->mailer = $new->mailer->withTemplate(
            new MessageBodyTemplate($this->aliases->get($value))
        );

        return $new;
    }

    /**
     * Sends an email.
     *
     * @param string $email Email.
     * @param array $params Params.
     */
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
