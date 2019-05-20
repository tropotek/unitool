<?php
namespace App\Ui;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2019 Michael Mifsud
 */
class Nav extends \Dom\Renderer\Renderer implements \Dom\Renderer\DisplayInterface
{

    /**
     * @return Nav
     */
    public static function create()
    {
        $obj = new static();
        return $obj;
    }

    /**
     * Execute the renderer.
     * Return an object that your framework can interpret and display.
     *
     * @return null|\Dom\Template|\Dom\Renderer\Renderer
     */
    public function show()
    {
        $template = $this->getTemplate();





        return $template;
    }


    public function __makeTemplate()
    {
        $html = <<<HTML
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">FVAS Toolbox</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="csv2html_sres.php"><i class="fa fa-file-text-o"></i> sres CSV2HTML</a></li>
                <li><a href="imgbw.php"><i class="fa fa-picture-o"></i> image2BW</a></li>
                <!--<li><a href="contact.php">Contact</a></li>-->
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
HTML;
        return \Dom\Loader::load($html);
    }



}