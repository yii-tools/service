<?php

declare(strict_types=1);

use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectionInterface;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    RouteCollectionInterface::class => RouteCollection::class,

    RouteCollectorInterface::class => static fn() => (new RouteCollector())
        ->addGroup(Group::create()->routes(Route::get('/home/index')->name('home'))),

    UrlGeneratorInterface::class => UrlGenerator::class,
];
