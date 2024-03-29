<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Support;

use Psr\Log\LoggerInterface;
use Yii\Service\Mailer;
use Yii\Service\ParameterInterface;
use Yii\Service\Redirect;
use Yii\Service\ViewTemplateRenderer;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\Symfony\Mailer as SymfonyMailer;

trait TestTrait
{
    private Aliases $aliases;
    private LoggerInterface $logger;
    private Mailer $mailer;
    private ParameterInterface $parameter;
    private Redirect $redirect;
    private ViewTemplateRenderer $viewTemplateRenderer;
    protected bool $writeToFiles = true;

    public function setup(): void
    {
        $this->createContainer();
    }

    public function tearDown(): void
    {
        unset(
            $this->aliases,
            $this->logger,
            $this->mailer,
            $this->parameter,
            $this->redirect,
            $this->viewTemplateRenderer,
        );
    }

    private function createContainer(): void
    {
        $config = new Config(
            new ConfigPaths(dirname(__DIR__, 2), 'config', 'vendor'),
            'tests',
        );

        $mailerInterfaceOverride = [
            MailerInterface::class => $this->writeToFiles ? FileMailer::class : SymfonyMailer::class,
        ];

        $definitions = array_merge($config->get('di-web'), $mailerInterfaceOverride);
        $containerConfig = ContainerConfig::create()->withDefinitions(array_merge($definitions));
        $container = new Container($containerConfig);

        $this->aliases = $container->get(Aliases::class);
        $this->logger = $container->get(LoggerInterface::class);
        $this->mailer = $container->get(Mailer::class);
        $this->parameter = $container->get(ParameterInterface::class);
        $this->redirect = $container->get(Redirect::class);
        $this->viewTemplateRenderer = $container->get(ViewTemplateRenderer::class);
    }
}
