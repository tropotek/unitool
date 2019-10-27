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

  function clearPanel() {
    $('#model-panel').empty();
    $('.src-link').remove();
  }


  $('#model').on('click', function () {
    clearPanel();
  });

  $('a[href$=".stl"]').on('click', function () {
    clearPanel();
    var madeleine = new Madeleine({
      target: 'model-panel', // target div id
      data: $(this).attr('href'), // data path
      path: './js/Madeleine/src'  // path to source directory from current html file
    });
    var srcLink = $('<p class="src-link">Model Source: <a  target="_blank"></a></p>');
    srcLink.find('a').attr('href', $(this).data('src')).text($(this).data('src'));
    $('div.panel').append(srcLink);


    return false;
  });




});

