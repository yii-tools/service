<?php

declare(strict_types=1);

/**
 * @var \Yiisoft\Translator\TranslatorInterface $translator Translator instance.
 * @var array $params
 */
?>

<?= $translator->translate($params['message'])  ?>
<p><?= $translator->translate($params['username']) ?></p>
