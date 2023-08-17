<?php
/**
 * @var $this \Yiisoft\View\WebView
 * @var $content string
 * @var string|null $csrfToken
 */
$csrfToken = $csrfToken ?? null;
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html>
<head>
    <?php if ($csrfToken !== null): ?>
    <meta name="csrf-token" content="<?= $csrfToken ?>">
    <?php endif ?>
    <title>Main Theme</title>
</head>
<body>
<?php $this->beginBody(); ?>

<?= $content ?>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage();
