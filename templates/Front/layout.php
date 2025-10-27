<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? SITE_TITLE) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta['description'] ?? '') ?>">
</head>
<body>
<nav><a href="/">Home</a> | <a href="/admin/">Admin</a></nav>
<main>
    <?= $content ?? '' ?>
</main>

<footer>
    <p>&copy; 2025 Мой сайт. Все права защищены.        </p>
</footer>
</div>

</body>
</html>