<!DOCTYPE html>
<html style="background:#111;color:#eee">
<head>
    <title><?php $this->block('title', 'Dark Theme'); ?></title>
    <meta charset="utf-8">
</head>
<body>
<header style="color:gold">
    <?php $this->block('header', '<h1>Dark Site</h1>'); ?>
</header>
<main>
    <?php $this->block('content', '<p>Dark content</p>'); ?>
</main>
<footer style="color:gray">
    <?php $this->block('footer', '<p>Dark &copy; 2025</p>'); ?>
</footer>
</body>
</html>