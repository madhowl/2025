<!DOCTYPE html>
<html>
<head>
    <title><?php $this->block('title', 'Default Title'); ?></title>
    <meta charset="utf-8">
</head>
<body>
<header>
    <?php $this->block('header', '<h1>My Site</h1>'); ?>
</header>

<main>
    <?php $this->block('content', '<p>No content</p>'); ?>
</main>

<footer>
    <?php $this->block('footer', '<p>&copy; 2025</p>'); ?>
</footer>
</body>
</html>