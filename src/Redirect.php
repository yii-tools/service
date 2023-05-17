<?php

declare(strict_types=1);

namespace Yii\Service;

use Stringable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Router\UrlGeneratorInterface;

final class Redirect
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /** @psalm-param array<string, Stringable|null|scalar> $arguments */
    public function run(
        string $url,
        int $code = 302,
        array $arguments = [],
        array $queryParameters = []
    ): ResponseInterface {
        return $this->responseFactory
            ->createResponse($code)
            ->withHeader('Location', $this->urlGenerator->generate($url, $arguments, $queryParameters));
    }
}
