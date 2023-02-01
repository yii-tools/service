<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Support;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Yii\Service\MailerService;
use Yii\Service\ParameterService;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Log\Logger;
use Yiisoft\Mailer\FileMailer;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\MessageBodyRenderer;
use Yiisoft\Mailer\MessageBodyTemplate;
use Yiisoft\Mailer\MessageFactory;
use Yiisoft\Mailer\MessageFactoryInterface;
use Yiisoft\Mailer\Symfony\Mailer;
use Yiisoft\Mailer\Symfony\Message;
use Yiisoft\Translator\CategorySource;
use Yiisoft\Translator\IntlMessageFormatter;
use Yiisoft\Translator\Message\Php\MessageSource;
use Yiisoft\Translator\MessageFormatterInterface;
use Yiisoft\Translator\Translator;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\View\View;

trait TestTrait
{
    protected Aliases $aliases;
    protected LoggerInterface $logger;
    protected MailerService $mailer;
    protected ParameterService $parameter;
    private bool $writeToFiles = true;

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->aliases, $this->logger, $this->mailer);
    }

    protected function createContainer(): void
    {
        $container = new Container(
            ContainerConfig::create()->withDefinitions($this->config())
        );

        $this->aliases = $container->get(Aliases::class);
        $this->logger = $container->get(LoggerInterface::class);
        $this->mailer = $container->get(MailerService::class);
        $this->parameter = $container->get(ParameterService::class);
    }

    private function config(): array
    {
        $params = $this->params();

        return [
            Aliases::class => [
                'class' => Aliases::class,
                '__construct()' => [
                    [
                        '@root' => __DIR__,
                        '@resources' => '@root/resources',
                        '@runtime' => '@root/runtime',
                    ],
                ],
            ],

            EventDispatcherInterface::class => Dispatcher::class,

            FileMailer::class => [
                'class' => FileMailer::class,
                '__construct()' => [
                    'path' => DynamicReference::to(fn (Aliases $aliases) => $aliases->get(
                        $params['yiisoft/mailer']['fileMailer']['fileMailerStorage'],
                    )),
                ],
            ],

            ListenerProviderInterface::class => Provider::class,

            LoggerInterface::class => Logger::class,

            MailerInterface::class => $this->writeToFiles ? FileMailer::class : Mailer::class,

            MessageBodyRenderer::class => [
                'class' => MessageBodyRenderer::class,
                '__construct()' => [
                    Reference::to(View::class),
                    DynamicReference::to(static fn (Aliases $aliases) => new MessageBodyTemplate(
                        $aliases->get($params['yiisoft/mailer']['messageBodyTemplate']['viewPath']),
                    )),
                ],
            ],

            MessageFactoryInterface::class => [
                'class' => MessageFactory::class,
                '__construct()' => [
                    Message::class,
                ],
            ],

            MessageFormatterInterface::class => IntlMessageFormatter::class,

            MessageReaderInterface::class => [
                'class' => MessageSource::class,
                '__construct()' => [
                    DynamicReference::to(static fn (Aliases $aliases) => $aliases->get('@message')),
                ],
            ],

            ParameterService::class => [
                'class' => ParameterService::class,
                '__construct()' => [$this->applicationParams()],
            ],

            TranslatorInterface::class => [
                'class' => Translator::class,
                '__construct()' => ['en'],
                'addCategorySources()' => [Reference::to('translation.test')],
            ],

            TransportInterface::class => $params['yiisoft/mailer']['useSendmail']
                ? SendmailTransport::class
                : static function (EsmtpTransportFactory $esmtpTransportFactory) use ($params): TransportInterface {
                    return $esmtpTransportFactory->create(new Dsn(
                        $params['symfony/mailer']['esmtpTransport']['scheme'],
                        $params['symfony/mailer']['esmtpTransport']['host'],
                        $params['symfony/mailer']['esmtpTransport']['username'],
                        $params['symfony/mailer']['esmtpTransport']['password'],
                        $params['symfony/mailer']['esmtpTransport']['port'],
                        $params['symfony/mailer']['esmtpTransport']['options'],
                    ));
                },

            View::class => [
                'class' => View::class,
                '__construct()' => [
                    'basePath' => __DIR__ . '/resources/mail',
                ],
            ],

            'translation.test' => static function (Aliases $aliases, MessageFormatterInterface $messageFormatter) {
                $messageSource = new MessageSource($aliases->get('@resources'));

                return new CategorySource('test', $messageSource, $messageFormatter);
            },
        ];
    }

    private function applicationParams(): array
    {
        return [
            'app' => [
                'name' => 'Yii Demo',
                'adminEmail' => 'test@example.com',
            ],
        ];
    }

    private function params(): array
    {
        return [
            'yiisoft/mailer' => [
                'messageBodyTemplate' => [
                    'viewPath' => '@resources/mail',
                ],
                'fileMailer' => [
                    'fileMailerStorage' => '@runtime/mail',
                ],
                'useSendmail' => false,
            ],
            'symfony/mailer' => [
                'esmtpTransport' => [
                    'scheme' => 'smtps', // "smtps": using TLS, "smtp": without using TLS.
                    'host' => 'smtp.example.com',
                    'port' => 465,
                    'username' => 'admin@example.com',
                    'password' => '',
                    'options' => [], // See: https://symfony.com/doc/current/mailer.html#tls-peer-verification
                ],
            ],
        ];
    }
}
