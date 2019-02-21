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
<style>
.csv-table {
  margin: 10px auto;
}
.csv-table table {
  border-color: #CCC;
}
.csv-table table td {
  border-color: #CCC;
  padding: 5px;

}



</style>
</head>
<body>


  <nav class="navbar navbar-inverse navbar-fixed-top" var="nav"></nav>

  <div class="container-fluid">
    <div class="content" var="content">
      <h1>SRES CSV2HTML</h1>
      <p>Convert a sres CSV to HTML table</p>
      <pre>It needs to produce a table, with the text in column A of INPUT.CSV converted to a link in a new window, pointing to the URL in column F (Single mode link).
No CSS; a couple of basic table attributes given in OUTPUT.HTML are all that's needed.</pre>
      <hr/>

      <div var="alert" choice="alert"></div>
      <form id="upload" method="post" class="form-horizontal" role="form">
        <div class="text-danger" var="form-error" choice="form-error"></div>
        <div class="form-group">
          <label for="contactEmail" class="col-sm-3 control-label">
            <b>Sres CSV File</b>
          </label>
          <div class="col-sm-3">
            <input type="file" name="csv-file" id="fid-csv-file" />
            <div class="text-danger" var="csv-file-error" choice="csv-file-error"></div>
          </div>
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary" name="submit">Convert!!!!</button>
          </div>
        </div>


      </form>
      <hr/>

      <div choice="csv-table">
        <div class="csv-table" var="csv-table"></div>
        <div>
          <a href="#" class="btn btn-success" title="Download Html File" target="_blank" var="download"><i class="fa fa-download"></i> Download</a>
          <a href="#" class="btn btn-primary" title="View Html File" target="_blank" var="view"><i class="fa fa-eye"></i> View</a>
          <a href="#" class="btn btn-default" title="Clear The Data" onclick="return confirm('Are you sure you want to remove the csv data from memory?');" var="clear"><i class="fa fa-trash"></i> Clear</a>
        </div>
      </div>

    </div>
  </div>


</body>
</html>
<?php

use Tk\Form;
use Tk\Form\Field;
use Tk\Form\Event;

$config = \Tk\Config::getInstance();
$buff = trim(ob_get_clean());
$template = \Dom\Template::load($buff);
$template->replaceTemplate('nav', \App\Ui\Nav::create()->show());

$request = $config->getRequest();
if ($request->has('clear')) {
    $config->getSession()->remove('csvData');
    \Tk\Uri::create()->reset()->redirect();
}

if ($request->has('view') && $config->getSession()->has('csvData')) {
    $html = makeTable($config->getSession()->get('csvData'));
    echo $html;
    header('Content-Type: text/html; charset=utf-8');
    exit;
}

if ($request->has('down') && $config->getSession()->has('csvData')) {
    $html = makeTable($config->getSession()->get('csvData'));
    $filename = 'table.html';
    echo $html;
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    exit;
}

// Make the table HTMl and return it
function makeTable($csvData) {
    $tpl = <<<HTML
<table var="table" cellpadding="4" cellspacing="0" border="1">
  <tr var="row" repeat="row">
    <td var="cell"><a href="#" var="url" target="_blank">&nbsp;</a></td>
  </tr>
</table>
HTML;
    $template = \Dom\Loader::load($tpl);

    foreach ($csvData as $name => $url) {
      $row = $template->getRepeat('row');
      $row->insertText('url', $name);
      $row->setAttr('url', 'href', $url);
      $row->appendRepeat();
    }

    return $template->toString();
}


$form = Form::create('upload');
/** @var \Tk\Form\Field\File $fileField */
$fileField = $form->appendField(new Field\File('csv-file'));
$fileField->setMaxFileSize(1024*1024*5);
$form->appendField(new Event\Button('submit', function (\Tk\Form $form)
{
    $config = \Tk\Config::getInstance();
    /** @var \Tk\Form\Field\File $fileField */
    $fileField = $form->getField('csv-file');
    $fileField->isValid();

    if ($fileField->hasFile()) {
        if(\Tk\File::getExtension($fileField->getUploadedFile()->getFilename()) != 'csv') {
            $fileField->addError('Invalid File Type. (.csv only)');
        }
    }
    if ($form->hasErrors() || !$fileField->isValid()) {
        \Tk\Alert::addError('Form has errors');
        return;
    }

    if ($fileField->hasFile()) {
        \Tk\Alert::addSuccess('File Successfully imported');
        $csvData = array();
        if (($handle = fopen($fileField->getUploadedFile()->getFile(), 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($data);
                if ($data[0] == 'Column name' || $data[0] == '!BARCODE') continue;
                $name = '';
                $url = '';
                for ($c=0; $c < $num; $c++) {
                    if ($c == 0) {   // Column Name
                        $name = $data[$c];
                    } else if ($c == 5) {    // Single Mode Link
                        $url = $data[$c];
                    }
                }
                $csvData[$name] = $url;
            }
            ksort($csvData, \SORT_NATURAL);
            $config->getSession()->set('csvData', $csvData);
            fclose($handle);
        }
    }

    \Tk\Uri::create()->redirect();
}
));
$form->execute();
// Render the form
\Tk\Form\Renderer\DomStatic::create($form, $template)->show();

// Render CSV data
$csvData = $config->getSession()->get('csvData');
if ($csvData) {
    $template->appendHtml('csv-table', makeTable($csvData));
    $template->setChoice('csv-table');
    $template->setAttr('download', 'href', \Tk\Uri::create()->set('down'));
    $template->setAttr('view', 'href', \Tk\Uri::create()->set('view'));
    $template->setAttr('clear', 'href', \Tk\Uri::create()->set('clear'));
}



if (\Tk\AlertCollection::hasMessages()) {
    $template->appendTemplate('alert', \Tk\AlertCollection::getInstance()->show());
    $template->setChoice('alert');
}
echo $template->toString();
