<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой сайт</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Мой сайт</h1>
    </div>

    <div class="nav">
        <a href="/">Главная</a>
        <a href="/articles">Статьи</a>
        <a href="calc">Калькулятор</a>
        <a href="#news">Новости</a>
        <a href="#contact">Контакты</a>
    </div>

    <div class="content">
        <div class="sidebar">
            <?php include_once(TEMPLATES_PATH . '/sidebar.php'); ?>
        </div>

        <div class="articles">
            <h2>POSTS LIST</h2>
            <div class="card-deck">
                <?php foreach ($data['posts'] as $post) {
                    echo '<div class="card" >
                      <img src="' . $post['meta']['cover_image'] . '" class="card-img-top" alt="' . $post['meta']['title'] . '">
                      <div class="card-body">
                        <h5 class="card-title">' . $post['meta']['title'] . '</h5>
                        <p class="card-text">' . $post['meta']['description'] . '</p>
                        <a href="#" class="btn btn-primary">Подробнее..</a>
                      </div>
                    </div>';
                }; ?>
            </div>
        </div>
    </div>


    <footer>
        <p>&copy; 2025 Мой сайт. Все права защищены. </p>
    </footer>
</div>

</body>
</html>