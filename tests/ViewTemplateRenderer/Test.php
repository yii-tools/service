<?php

declare(strict_types=1);

namespace Yii\Service\Tests\ViewTemplateRenderer;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;
use Yii\Support\Assert;
use Yiisoft\DataResponse\DataResponse;

final class Test extends TestCase
{
    use TestTrait;

    public function testRender(): void
    {
        $this->aliases->set('@layout', dirname(__DIR__) . '/data/resources/view');

        /** @var DataResponse $dataResponse */
        $dataResponse = $this->viewTemplateRenderer
            ->withViewPath(dirname(__DIR__) . '/data/resources/view')
            ->render('view', ['title' => 'View'], ['title' => 'Layout']);

        Assert::equalsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                    <title>Layout</title>
            </head>
            <body>

            <h1>View</h1>

            </body>
            </html>

            HTML,
            $dataResponse->getData(),
        );
    }

    public function testWithLayoutParameters(): void
    {
        $this->aliases->set('@layout', dirname(__DIR__) . '/data/resources/view');

        /** @var DataResponse $dataResponse */
        $dataResponse = $this->viewTemplateRenderer
            ->withViewPath(dirname(__DIR__) . '/data/resources/view')
            ->withLayoutParameters(['csrfToken' => 'test'])
            ->render('view', ['title' => 'View'], ['title' => 'Layout']);

        Assert::equalsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                    <meta name="csrf-token" content="test">
                    <title>Layout</title>
            </head>
            <body>

            <h1>View</h1>

            </body>
            </html>

            HTML,
            $dataResponse->getData(),
        );
    }
}
