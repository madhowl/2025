{% extends "base.html.twig" %}
        {% block content %}
        <div class="article">
            <h3><?php echo $article['title']?></h3>
            <p><?php echo $article['content']?></p>
        </div>
        {% endblock %}



