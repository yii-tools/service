<?php

declare(strict_types=1);

use HttpSoft\Message\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    ResponseFactoryInterface::class => ResponseFactory::class,
];
