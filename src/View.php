<?php

declare(strict_types=1);

namespace Forge\Service;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\View\ViewContextInterface;
use Yiisoft\View\WebView;

final class View implements ViewContextInterface
{
    private array $commonParameters = [];
    private string $layout = '@layout/main';
    private array $layoutParameters = [];
    private string $viewPath = '';
    private array $viewParameters = [];

    public function __construct(
        private Aliases $aliases,
        private DataResponseFactoryInterface $dataResponseFactory,
        private WebView $webView
    ) {
    }

    public function render(string $view, array $viewParameters = []): ResponseInterface
    {
        $webView = $this->webView->withContext($this);

        $layoutParameters = array_merge($this->commonParameters, $this->layoutParameters);
        $viewParameters = array_merge($this->commonParameters, $this->viewParameters, $viewParameters);

        $parameters = array_merge($layoutParameters, ['content' => $webView->render($view, $viewParameters)]);

        return $this->dataResponseFactory->createResponse($this->renderFile($this->layout, $parameters));
    }

    public function renderFile(string $file, array $parameters = []): string
    {
        $webView = $this->webView->withContext($this);

        $file = $this->findFile($file);

        return $webView->renderFile($file, $parameters);
    }

    public function getViewPath(): string
    {
        $viewPath = $this->viewPath;

        if ('' === $viewPath) {
            $viewPath = $this->webView->getBasePath();
        }

        return $this->aliases->get($viewPath);
    }

    public function withContext(ViewContextInterface $context): ViewContextInterface
    {
        $new = clone $this;
        $new->webView = $new->webView->withContext($context);

        return $new;
    }

    public function withCommonParameters(array $values): self
    {
        $new = clone $this;
        $new->commonParameters = $values;

        return $new;
    }

    public function withLayoutParameters(array $values): self
    {
        $new = clone $this;
        $new->layoutParameters = $values;

        return $new;
    }

    public function withViewPath(string $value): self
    {
        $new = clone $this;
        $new->viewPath = $value;

        return $new;
    }

    public function withViewParameters(array $values): self
    {
        $new = clone $this;
        $new->viewParameters = $values;

        return $new;
    }

    /**
     * Finds a file based on the given file path or alias.
     *
     * @param string $file The file path or alias.
     *
     * @return string The path to the file with the file extension.
     */
    private function findFile(string $file): string
    {
        $file = $this->aliases->get($file);

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }

        return $file . '.' . $this->webView->getDefaultExtension();
    }
}
