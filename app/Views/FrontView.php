<?php

namespace App\Views;

use App\Traits\Helper;

class FrontView
{
    use Helper;
    protected $content;
    protected $templateName;
    protected $templatePath;

    public function __construct($thema = DEFAULT_THEMA)
    {
        $this->templatePath = TEMPLATES_PATH . DIRECTORY_SEPARATOR . $thema . DIRECTORY_SEPARATOR;
    }

    public function render($template, $data = [])
    {
         include $this->templatePath . $template;

    }

    public function renderIndexPage()
    {
        $this->templatePath . 'layout.php';
    }

}