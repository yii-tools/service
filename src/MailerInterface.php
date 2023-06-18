<?php

declare(strict_types=1);

namespace Yii\Service;

/**
 * Provides a way to send email messages.
 */
interface MailerInterface
{
    /**
     * Returns a new instance with the specified attachments.
     *
     * @param array $value Attachments.
     *
     * @psalm-param string[] $value
     */
    public function attachments(array $value): self;

    /**
     * Returns a new instance with the specified from.
     *
     * @param string $value From.
     */
    public function from(string $value): self;

    /**
     * Returns a new instance with the specified layout.
     *
     * @param array $value Layout.
     *
     * @psalm-param array<string, string>|string|null $value
     */
    public function layout(array|string|null $value): self;

    /**
     * Sends an email.
     *
     * @param string $email Email.
     * @param array $params Params.
     */
    public function send(string $email, array $params = []): bool;

    /**
     * Returns a new instance with the specified signature image.
     *
     * @param string $value Signature image.
     */
    public function signatureImage(string $value): self;

    /**
     * Returns a new instance with the specified signature text.
     *
     * @param string $value Signature text.
     */
    public function signatureText(string $value): self;

    /**
     * Returns a new instance with the specified subject.
     *
     * @param string $value Subject.
     */
    public function subject(string $value): self;

    /**
     * Returns a new instance with the specified translator category.
     *
     * @param string $value Translator category.
     */
    public function translatorCategory(string $value): self;

    /**
     * Returns a new instance with the specified view path.
     *
     * @param string $value View path.
     */
    public function viewPath(string $value): self;
}
