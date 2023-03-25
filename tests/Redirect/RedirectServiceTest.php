<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Parameter;

use PHPUnit\Framework\TestCase;
use Yii\Service\Tests\Support\TestTrait;

final class RedirectServiceTest extends TestCase
{
    use TestTrait;

    public function testRedirect(): void
    {
        $this->createContainer();

        $redirect = $this->redirect->run('home');

        $this->assertSame(['Location' => ['/home/index']], $redirect->getHeaders());
    }
}
