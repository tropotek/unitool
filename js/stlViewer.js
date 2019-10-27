/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2019 Michael Mifsud
 */



jQuery(function ($) {
  Lily.ready({
    target: 'model-panel',  // target div id
    file: 'model',  // file input id
    //path: './js/models' // path to source directory from current html file
    path: './js/Madeleine/src' // path to source directory from current html file
  });


  $('#model').on('click', function () {
    $('#model-panel').empty();
  });

  $('a[href$=".stl"]').on('click', function () {
    $('#model-panel').empty();
    var madeleine = new Madeleine({
      target: 'model-panel', // target div id
      data: $(this).attr('href'), // data path
      path: './js/Madeleine/src'  // path to source directory from current html file
    });
    return false;
  });




});

