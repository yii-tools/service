## Mailer service

The mailer service which is a wrapper around of `\Symfony\Component\Mailer\MailerInterface` and `\Symfony\Component\Mailer\Transport\TransportInterface`.

Example of using `MailerService::class`:

```php
file: `src/Controller/ContactController.php`

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactForm;
use Psr\Http\Message\ServerRequestInterface;
use Yii\Service\MailerService;

final class ContactController
{
    public function run(
        MailerService $mailerService,
        ServerRequestInterface $serverRequest,
        ViewRenderer $viewRenderer
    ): ResponseInterface {
        /** @psalm-var string[] $body */
        $body = $serverRequest->getParsedBody();
        $method = $serverRequest->getMethod();

        $contactForm = new ContactForm();

        if ($method === Method::POST && $contactForm->load($body)) {
            $mailer
                ->attachmentsFromPath('@resources/data/document.pdf')
                ->from($contactForm->getEmail())
                ->signatureImage('@images/mail-yii3-signature.png')
                ->subject($contactForm->getSubject())
                ->viewPath('@storage/mailer')
                ->send(
                    'admin@example.com',
                    [
                        'message' => $contactForm->getMessage(),
                        'username' => $contactForm->getName(),
                    ],
                );
        }

        return $viewRenderer->render('contact/index', ['form' => $contactForm]);
    }
}
```

## Parameter service

The parameter service will help you to get parameters from the configuration file `config/parameters.php`.

Define parameters in the configuration file `config/parameters.php`:

```php
file: `config/parameters.php`

<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Yii Demo',
        'version' => '3.0',
    ],
];
```	

Example of using `ParameterService::class` in the controller or action:

```php
file: `src/Controller/SiteController.php`

<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Yii\Service\ParameterService;

final class SiteController
{
    public function run(
        ParameterService $parameterService,
        ServerRequestInterface $serverRequest,
        ViewRenderer $viewRenderer
    ): ResponseInterface {
        $appName = $parameterService->get('app.name');
        $appVersion = $parameterService->get('app.version');

        return $viewRenderer->render('site/index', ['appName' => $appName, 'appVersion' => $appVersion]);
    }
}
```

Example of using `ParameterService::class` in the view:

Config the view in the configuration file `config/param.php`:

```php
file: `config/param.php`

<?php

declare(strict_types=1);

use App\Service\ParameterService;
use Yiisoft\Definitions\Reference;

return [
    'yiisoft/view' => [
        'parameters' => [
            'parameterService' => Reference::to(ParameterService::class),
        ],
    ],
];
```

Example of using `ParameterService::class` in the view:

```php
file: `src/View/Site/index.php`

<?php

declare(strict_types=1);

/**
 * @var \App\Service\ParameterService $parameterService
 * @var \Yiisoft\View\WebView $this 
 */

$this->setTitle($parameterService->get('app.name'));

?>

<h1><?= $this->getTitle() ?></h1>
<p>Version: <?= $parameterService->get('app.version') ?></p>
```
