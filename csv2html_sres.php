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
      <h1>SRES CSV2HTML</h1>
      <p>Convert a sres CSV to HTML table</p>
      <pre>It needs to produce a table, with the text in column A of INPUT.CSV converted to a link in a new window, pointing to the URL in column F (Single mode link).
No CSS; a couple of basic table attributes given in OUTPUT.HTML are all that's needed.</pre>
      <hr/>

      <div var="alert" choice="alert"></div>
      <form id="upload" method="post" class="form-horizontal" role="form">
        <div class="text-danger" var="form-error" choice="form-error"></div>
        <div class="form-group">
          <label for="upload-csv-file" class="col-sm-3 control-label">Sres CSV File</label>
          <div class="col-sm-3">
            <input type="file" name="csv-file" id="fid-csv-file" />
            <div class="text-danger" var="csv-file-error" choice="csv-file-error"></div>
          </div>
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary" name="submit">Convert!!!!</button>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-3"></div>
          <div class="col-sm-3">
            <label for="upload-singleMode"><input type="checkbox" name="singleMode" value="singleMode" id="upload-singleMode" checked="checked"/> Single Mode</label> &nbsp;
            <label for="upload-bulkMode"><input type="checkbox" name="bulkMode" value="bulkMode" id="upload-bulkMode" /> Bulk Mode</label> &nbsp;
            <label for="upload-roleView"><input type="checkbox" name="roleView" value="roleView" id="upload-roleView" /> Role View</label>
          </div>
          <div class="col-sm-6"></div>
        </div>

      </form>
      <hr/>

      <div choice="csv-table">
        <div class="csv-table" var="csv-table"></div>
        <div>
          <a href="#" class="btn btn-success" title="Download Html File" var="download"><i class="fa fa-download"></i> Download</a> &nbsp;
          <a href="#" class="btn btn-primary" title="View Html File" target="_blank" var="view"><i class="fa fa-eye"></i> View</a> &nbsp;
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
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    exit;
}

if ($request->has('down') && $config->getSession()->has('csvData')) {
    $html = makeTable($config->getSession()->get('csvData'));
    $filename = 'table.html';
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    echo $html;
    exit;
}


function getLabel($str = '')
{
  switch ($str) {
      case 'singleMode':
          $str = 'Barcode Scanner Link';
          break;
      case 'bulkMode':
          $str = 'Bulk Mode Link';
          break;
      case 'roleView':
          $str = 'Roll Call Link';
          break;
      case 'roleView':
          $str = 'Roll Call Link';
          break;
  }
  return $str;
}

// Make the table HTMl and return it
function makeTable($csvData) {
    $tpl = <<<HTML
<table var="table" cellpadding="4" cellspacing="0" border="1">
  <tr var="row" repeat="row">
    <td var="cell" repeat="cell"><a href="#" var="url" target="_blank">&nbsp;</a></td>
  </tr>
</table>
HTML;
    $template = \Dom\Loader::load($tpl);

    foreach ($csvData as $name => $rowData) {
        $row = $template->getRepeat('row');

        if (count($rowData) > 1) {
            $cell = $row->getRepeat('cell');
            $cell->insertText('cell', $name);
            $cell->appendRepeat();
            foreach ($rowData as $key => $val) {
                $cell = $row->getRepeat('cell');
                $cell->insertText('url', getLabel($key));
                $cell->setAttr('url', 'title', \Tk\Str::ucSplit($key));
                $cell->setAttr('url', 'href', $val);
                $cell->appendRepeat();
            }
        } else {
            foreach ($rowData as $key => $val) {
                $cell = $row->getRepeat('cell');
                $cell->insertText('url', $name);
                $cell->setAttr('url', 'title', \Tk\Str::ucSplit($key));
                $cell->setAttr('url', 'href', $val);
                $cell->appendRepeat();
            }
        }

        $row->appendRepeat();
    }

    return $template->toString();
}


$form = Form::create('upload');
/** @var \Tk\Form\Field\File $fileField */
$fileField = $form->appendField(new Field\File('csv-file'));
$form->appendField(Field\Checkbox::create('singleMode'));
$form->appendField(Field\Checkbox::create('bulkMode'));
$form->appendField(Field\Checkbox::create('roleView'));
$form->appendField(Field\Checkbox::create('directAccess'));

$fileField->setMaxFileSize(1024*1024*5);
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
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $num = count($data);
                if ($data[0] == 'Column name' || $data[0] == '!BARCODE') continue;
                $name = '';
                $rowData = array();
                for ($c=0; $c < $num; $c++) {
                    if ($c == 0) {            // Column Name
                        $name = $data[$c];
                    } else if ($c == 5 && $form->getFieldValue('singleMode')) {     // Single Mode Link
                        $rowData['singleMode'] = $data[$c];
                    } else if ($c == 6 && $form->getFieldValue('bulkMode')) {     // Bulk Mode Link
                        $rowData['bulkMode'] = $data[$c];
                    } else if ($c == 7 && $form->getFieldValue('roleView')) {     // Role View Link
                        $rowData['roleView'] = $data[$c];
                    } else if ($c == 8 && $form->getFieldValue('directAccess')) {     // Student direct access link
                        $rowData['directAccess'] = $data[$c];
                    }
                }
                $csvData[$name] = $rowData;
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
    $template->setVisible('csv-table');
    $template->setAttr('download', 'href', \Tk\Uri::create()->reset()->set('down'));
    $template->setAttr('view', 'href', \Tk\Uri::create()->reset()->set('view'));
    $template->setAttr('clear', 'href', \Tk\Uri::create()->reset()->set('clear'));
}

if (\Tk\AlertCollection::hasMessages()) {
    $template->appendTemplate('alert', \Tk\AlertCollection::getInstance()->show());
    $template->setVisible('alert');
}
echo $template->toString();
