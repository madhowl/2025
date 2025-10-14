<?php include_once ('./templates/header.php');?>

<div class="content">
    <div class="sidebar">
        <?php include_once ('./templates/sidebar.php');?>
    </div>

    <div class="articles">
        <?php foreach ($articles as $article) { ?>
        <div class="article">
            <h3><?php echo $article['title']?></h3>
            <p><?php echo $article['content']?></p>
        </div>
        <?php };?>

    </div>
</div>

<?php include_once ('./templates/footer.php');?>
    