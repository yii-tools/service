<?php

declare(strict_types=1);

namespace Yii\Service;

use Yiisoft\Translator\TranslatorInterface;

final class Translator
{
    private string $category = 'app';

    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function t(string $message, array $params = [], string $category = ''): string
    {
        $categoryDefault = $this->category;

        if ($category === '') {
            $categoryDefault = $category;
        }

        return $this->translator->translate($message, $params, $categoryDefault);
    }

    public function category(string $category): void
    {
        $this->category = $category;
    }

    public function getLocale(): string
    {
        return $this->translator->getLocale();
    }
}
