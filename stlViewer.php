<?php
include(dirname(__FILE__) . '/_prepend.php');
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="chrome=1" />
<!--  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>-->
<!--  <meta name="viewport" content="width=device-width, initial-scale=1"/>-->
  <meta name="description" content=""/>
  <meta name="author" content=""/>

  <title>UniTool</title>

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

<link href="js/Madeleine/src/css/Madeleine.css" rel="stylesheet" />

<script src="js/Madeleine/src/lib/stats.js"></script>
<script src="js/Madeleine/src/lib/detector.js"></script>
<script src="js/Madeleine/src/lib/three.min.js"></script>
<script src="js/Madeleine/src/Madeleine.js"></script>
<script src="js/stlViewer.js"></script>

<style>
#model-panel {

}
</style>

</head>
<body>


  <nav class="navbar navbar-inverse navbar-fixed-top" var="nav"></nav>

  <div class="container-fluid">
    <div class="container">
      <h1>STL Viewer</h1>
      <p>View .stl files.</p>

      <hr/>

      <div var="alert" choice="alert"></div>

      <div class="row">

        <div class="col-md-12">
          <p>
            Example Models:
            <a href="js/models/horseLarynx-01.stl" data-src="https://www.thingiverse.com/thing:3941229">Horse Larynx (5.7M)</a> |
            <a href="js/models/PawSplint3.stl" data-src="https://www.thingiverse.com/thing:1580170">Cat Paw Splint (1.5M)</a> |
            <a href="js/models/CowLowerLeg_Scanned.stl" data-src="https://www.thingiverse.com/thing:3749674">Cow Lower Leg (9.7M)</a>
          </p>
          <hr/>
        </div>
        <div class="col-md-12">
          <form class="form-inline">
            <div class="form-group">
              <label for="fid-model" class="control-label">Upload STL File</label>
              <input type="file" name="model" id="model" accept=".stl" />
            </div>
          </form>
        </div>
      </div>

      <hr/>

      <div class="row">
        <div class="col-md-12">
          <div style="text-align: center;" class="panel">
            <span id="model-panel" class="model-panel" style="display: inline-block;margin-left: auto; margin-right: auto;"></span>
          </div>
        </div>
      </div>

    </div>
  </div>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>

</body>
</html>
<?php
$buff = trim(ob_get_clean());
$template = \Dom\Template::load($buff);
$template->replaceTemplate('nav', \App\Ui\Nav::create()->show());

$config = \Tk\Config::getInstance();
$request = $config->getRequest();


echo $template->toString();
