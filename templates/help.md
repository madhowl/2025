
📄 index.php — использование
```php

<?php
require_once 'TemplateEngine.php';

$engine = new TemplateEngine('default'); // или 'dark'

// Передача данных в шаблон
$engine->assign('name', 'Alice');

// Рендер шаблона
$engine->render('home');
``` 
 

Чтобы сменить тему, просто: 
```php
$engine->setTheme('dark');
$engine->render('home');
```
 
 
✅ Возможности 

    Поддержка наследования (extend)
    Блоки с fallback-содержимым
    Передача переменных (assign)
    Смена темы без изменения кода
     

 