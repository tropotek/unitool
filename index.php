<?php
include(dirname(__FILE__) . '/_prepend.php');
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>

  <title>Simple Tk Project</title>

  <!-- Styles -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous" />
  <link href="css/custom.css" rel="stylesheet" />

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap.min.js"></script>

</head>
<body>


  <nav class="navbar navbar-inverse navbar-fixed-top" var="nav"></nav>

  <div class="container-fluid">
    <div class="content" var="content">
      <h1>FVAS Toolbox</h1>
      <p>A site to host FVAS eLearning Tools and scripts...</p>
    </div>
  </div>


</body>
</html>
<?php
$buff = trim(ob_get_clean());
$template = \Dom\Template::load($buff);
$template->replaceTemplate('nav', \App\Ui\Nav::create()->show());


//$template->prependHtml('content', '<h2>This is the `Hello World` Dynamic text.</h2>');
//vd('Test Dump');
//\Tk\Log::warning('Test Log');


echo $template->toString();
