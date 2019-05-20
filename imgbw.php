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
  <script src="js/bwImage.js"></script>


<style>
.images .col-sm-6 {
  border: 1px solid #CCC;
}
.images img {
  margin: 10px auto;
}
input[type="file"] {
  display: inline-block;
}


</style>


</head>
<body>


  <nav class="navbar navbar-inverse navbar-fixed-top" var="nav"></nav>

  <div class="container-fluid">
    <div class="content">
      <h1>Image To Grayscale</h1>
      <p>Convert a JPEG PNG  or GIF to a black and white image.</p>

      <hr/>

      <div var="alert" choice="alert"></div>

      <form id="upload" method="post" class="form-inline" role="form" enctype="multipart/form-data">

        <div class="form-group">
          <label for="fid-file" class="control-label">Image File</label>
          <input type="file" name="file" id="fid-image" />
        </div>

        <div class="form-group">
          <label for="fid-maxWidth" class="control-label">Max Width:</label>
          <input type="text" name="maxWidth" id="fid-maxWidth" size="4" value="1000"/>
        </div>

        <div class="form-group">
          <label for="fid-maxHeight" class="control-label">Max Height:</label>
          <input type="text" name="maxHeight" id="fid-maxHeight" size="4" value="1000"/>
        </div>

      </form>

      <hr/>

      <div class="container img-panel" style="display: none;">

        <div class="row images text-center">
          <div class="col-sm-6">
            <img src="#" id="src-img" class="img-responsive" alt="" />
            <p class="src-size">999x999</p>
          </div>
          <div class="col-sm-6 text-center">
            <a href="#" class="btn-download" title="Click to download"><img src="#" id="dst-img" class="img-responsive" alt="" /></a>
            <p class="dst-size">999x999</p>
          </div>
        </div>
        <br/>
        <div>
          <a href="#" class="pull-right btn btn-success btn-download" title="Click to download"><i class="fa fa-download"></i> Download</a>
        </div>

      </div>

    </div>
  </div>


</body>
</html>
<?php
$buff = trim(ob_get_clean());
$template = \Dom\Template::load($buff);
$template->replaceTemplate('nav', \App\Ui\Nav::create()->show());

$config = \Tk\Config::getInstance();
$request = $config->getRequest();


echo $template->toString();
