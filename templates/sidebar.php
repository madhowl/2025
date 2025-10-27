<h2>Категории:</h2>
<ul>
    <?php
    if (isset($data['categories'])){
        foreach ($data['categories'] as $category){
            echo '<li><a href="/category/' . $category . '">' . $category . '</a></li>';
        }
    } else{
        echo 'Categories not found';
    }
    ?>
</ul>