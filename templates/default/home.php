<?php
$this->extend('layout');
$this->blockStart('title'); ?>
    Home Page
<?php $this->blockEnd();

$this->blockStart('content'); ?>
    <h2>Welcome!</h2>
    <p>Hello, <?= htmlspecialchars($name ?? 'Guest'); ?>!</p>
<?php $this->blockEnd();