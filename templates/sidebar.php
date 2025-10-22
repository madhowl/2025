<h2>Категории:</h2>
<ul>
    <?php
    if (isset($categories)){
        foreach ($categories as $category){
            echo '<li><a href="category/' . $category . '">' . $category . '</a></li>';
        }
    } else{
        echo 'Categories not found';
    }
    ?>
</ul>