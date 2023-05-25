<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Redirect;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class RedirectServiceTest extends TestCase
{
    use TestTrait;

    public function testRedirect(): void
    {
        $redirect = $this->redirect->run('home');

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index']], $redirect->getHeaders());
    }

    public function testRedirectWithArguments(): void
    {
        $redirect = $this->redirect->run('home', 302, ['id' => 1]);

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index?id=1']], $redirect->getHeaders());
    }

    public function testRedirectWithCode200(): void
    {
        $redirect = $this->redirect->run('home', 200);

        $this->assertSame(200, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index']], $redirect->getHeaders());
    }

    public function testRedirectWithQueryParams(): void
    {
        $redirect = $this->redirect->run('home', 302, [], ['id' => 1]);

        $this->assertSame(302, $redirect->getStatusCode());
        $this->assertSame(['Location' => ['/home/index?id=1']], $redirect->getHeaders());
    }
}
