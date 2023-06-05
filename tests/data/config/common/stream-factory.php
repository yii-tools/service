<?php

declare(strict_types=1);

use HttpSoft\Message\StreamFactory;
use Psr\Http\Message\StreamFactoryInterface;

return [
    StreamFactoryInterface::class => StreamFactory::class,
];
