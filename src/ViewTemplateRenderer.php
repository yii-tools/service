<?php

declare(strict_types=1);

namespace Yii\Service;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\View\Theme;
use Yiisoft\View\ViewContextInterface;
use Yiisoft\View\WebView;

final class ViewTemplateRenderer implements ViewContextInterface
{
    private string $layoutFile = '';
    private array $layoutParameters = [];
    private string $viewPath = '';

    public function __construct(
        private readonly Aliases $aliases,
        private readonly DataResponseFactoryInterface $dataResponse,
        private readonly WebView $webView,
    ) {
    }

    public function getViewPath(): string
    {
        return $this->findAliases($this->viewPath);
    }

    public function render(string $view, array $viewParameters = [], array $layoutParameters = []): ResponseInterface
    {
        $content = $this->renderView($view, $viewParameters);

        $parameters = ['content' => $content, ...$layoutParameters, ...$this->layoutParameters];

        $layout = $this->findAliases($this->layoutFile);

        $content = $this->webView->renderFile($layout, $parameters);

        return $this->dataResponse->createResponse($content);
    }

    public function withLayoutFile(string $layoutFile): self
    {
        $new = clone $this;
        $new->layoutFile = $layoutFile;

        return $new;
    }

    public function withLayoutParameters(array $parameters): self
    {
        $new = clone $this;
        $new->layoutParameters = $parameters;

        return $new;
    }

    public function withTheme(Theme $theme): self
    {
        $new = clone $this;
        $new->webView->setTheme($theme);

        return $new;
    }

    public function withViewPath(string $path): self
    {
        $new = clone $this;
        $new->viewPath = $path;

        return $new;
    }

    private function findAliases(string $aliases): string
    {
        if (substr($aliases, 0, 1) === '@') {
            return $this->aliases->get($aliases);
        }

        return $aliases;
    }

    private function renderView(string $view, array $viewParameters): string
    {
        return $this->webView->withContext($this)->render($view, $viewParameters);
    }
}
