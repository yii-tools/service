<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Redirect;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class Test extends TestCase
{
    use TestTrait;

    public function testRun(): void
    {
        $redirect = $this->redirect->run('home');

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index']], $redirect->getHeaders());
    }

    public function testWithArguments(): void
    {
        $redirect = $this->redirect->run('home', 302, ['id' => 1]);

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index?id=1']], $redirect->getHeaders());
    }

    public function testWithCode200(): void
    {
        $redirect = $this->redirect->run('home', 200);

        $this->assertSame(200, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index']], $redirect->getHeaders());
    }

    public function testWithQueryParams(): void
    {
        $redirect = $this->redirect->run('home', 302, [], ['id' => 1]);

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index?id=1']], $redirect->getHeaders());
    }
}
