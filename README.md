<p align="center">
    <a href="https://github.com/yii-tools/service" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/121752654?s=200&v=4" height="100px">
    </a>
    <h1 align="center">Service for YiiFramework v.3.0.</h1>
    <br>
</p>

## Requirements

The minimun version of PHP required by this package is PHP 8.1.

For install this package, you need [composer](https://getcomposer.org/), and fileinfo PHP extension.

## Install

```shell
composer require yii-tools/service
```

## Usage

For use this package, you need read the [docs](docs/index.md).

## Checking dependencies

This package uses [composer-require-checker](https://github.com/maglnet/ComposerRequireChecker) to check if all dependencies are correctly defined in `composer.json`.

To run the checker, execute the following command:

```shell
composer run check-dependencies
```

## Mutation testing

Mutation testing is checked with [Infection](https://infection.github.io/). To run it:

```shell
composer run mutation
```

## Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
composer run psalm
```

## Testing

The code is tested with [PHPUnit](https://phpunit.de/). To run tests:

```
composer run test
```

## CI status

[![build](https://github.com/yii-tools/service/actions/workflows/build.yml/badge.svg)](https://github.com/yii-tools/service/actions/workflows/build.yml)
[![codecov](https://codecov.io/gh/yii-tools/service/branch/main/graph/badge.svg?token=MF0XUGVLYC)](https://codecov.io/gh/yii-tools/service)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyii-tools%2Fservice%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/yii-tools/service/main)
[![static analysis](https://github.com/yii-tools/service/actions/workflows/static.yml/badge.svg)](https://github.com/yii-tools/service/actions/workflows/static.yml)
[![type-coverage](https://shepherd.dev/github/yii-tools/service/coverage.svg)](https://shepherd.dev/github/yii-tools/service)
[![StyleCI](https://github.styleci.io/repos/513988564/shield?branch=main)](https://github.styleci.io/repos/513988564?branch=main)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Our social networks

[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/Terabytesoftw)
