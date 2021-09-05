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
      <h1>EMS CSV Placement Converter</h1>
      <p>Convert an EMS placement manager CSV file to a formatted CSV with placements in one row</p>

      <hr/>

      <div var="alert" choice="alert"></div>
      <form id="upload" method="post" class="form-horizontal" role="form">
        <div class="text-danger" var="form-error" choice="form-error"></div>
        <div class="form-group">
          <label for="upload-csv-file" class="col-sm-4 control-label">Placement CSV</label>
          <div class="col-sm-2">
            <input type="file" name="csv-file" id="fid-csv-file" />
            <div class="text-danger" var="csv-file-error" choice="csv-file-error"></div>
          </div>
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary" name="submit">Convert</button>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-3"></div>
          <div class="col-sm-5 text-center">
            <label for="upload-sName"><input type="checkbox" name="sName" value="sName" id="upload-sName" checked="checked"/> Include Student Name</label>
          </div>
          <div class="col-sm-6"></div>
        </div>

      </form>
      <hr/>

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



$form = Form::create('upload');
/** @var \Tk\Form\Field\File $fileField */
$fileField = $form->appendField(new Field\File('csv-file'));
$fileField->setMaxFileSize(1024*1024*5);
$form->appendField(Field\Checkbox::create('sName'));

$form->appendField(new Event\Button('submit', function (\Tk\Form $form)
{
    $config = \Tk\Config::getInstance();
    /** @var \Tk\Form\Field\File $fileField */
    $fileField = $form->getField('csv-file');
    $fileField->isValid();

    if ($fileField->hasFile()) {
        if($fileField->getUploadedFile()->getClientOriginalExtension() != 'csv') {
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
        if (($handle = fopen($fileField->getUploadedFile()->getPathname(), 'r')) !== false) {

            $uid = '';
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($data);
                // Skip headers
                if (strtolower($data[0]) == strtolower('id'))  continue;

                $rowData = array();
                if ($data[0] != $uid) {
                    $uid = trim($data[17]);
                    $rowData[] = $uid;
                    if ($form->getFieldValue('sName'))
                      $rowData[] = $data[1];
                }
                if (isset($csvData[$uid])) $rowData = $csvData[$uid];

                $rowData[] = $data[2];    // CoName
                $rowData[] = $data[10];    // units

                $csvData[$uid] = $rowData;
            }
//            ksort($csvData, \SORT_NATURAL);
//            $config->getSession()->set('csvData', $csvData);
            fclose($handle);
        }
    }

    // Download converted csv
    header('Content-Type: text/html; charset=utf-8');
    header("Content-Type:application/csv");
    //header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.\Tk\Date::create()->format(\Tk\Date::FORMAT_ISO_DATE).'_ems-placements.csv"');
    $out = fopen('php://output', 'w');
    foreach ($csvData as $row)
      fputcsv($out, $row);
    fclose($out);
    exit();
    //vd($csvData);
    //\Tk\Uri::create()->redirect();
}
));
$form->execute();

// Render the form
\Tk\Form\Renderer\DomStatic::create($form, $template)->show();

// Render CSV data
//$csvData = $config->getSession()->get('csvData');
//if ($csvData) {
//    $template->appendHtml('csv-table', makeTable($csvData));
//    $template->setVisible('csv-table');
//    $template->setAttr('download', 'href', \Tk\Uri::create()->reset()->set('down'));
//    $template->setAttr('view', 'href', \Tk\Uri::create()->reset()->set('view'));
//    $template->setAttr('clear', 'href', \Tk\Uri::create()->reset()->set('clear'));
//}

//if (\Tk\AlertCollection::hasMessages()) {
//    $template->appendTemplate('alert', \Tk\AlertCollection::getInstance()->show());
//    $template->setVisible('alert');
//}






echo $template->toString();
