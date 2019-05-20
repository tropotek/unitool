/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2019 Michael Mifsud
 */


jQuery(function ($) {


  function gray(imgObj) {
    var canvas = document.createElement('canvas');
    var canvasContext = canvas.getContext('2d');

    var imgW = imgObj.naturalWidth;
    var imgH = imgObj.naturalHeight;
    canvas.width = imgW;
    canvas.height = imgH;

    canvasContext.drawImage(imgObj, 0, 0);
    var imgPixels = canvasContext.getImageData(0, 0, imgW, imgH);

    for(var y = 0; y < imgPixels.height; y++){
      for(var x = 0; x < imgPixels.width; x++){
        var i = (y * 4) * imgPixels.width + x * 4;
        var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
        imgPixels.data[i] = avg;
        imgPixels.data[i + 1] = avg;
        imgPixels.data[i + 2] = avg;
      }
    }
    canvasContext.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
    return canvas.toDataURL();
  }

  function readImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      $('#src-img').attr('src', '');
      $('#dst-img').attr('src', '');
      $('.img-panel').hide();
      reader.onload = function (e) {
        // TODO: Check the file type/ext
        var allowExtension = ".jpg,.bmp,.gif,.png";
        console.log(input.value);
        var ext = input.value.substring(input.value.lastIndexOf(".") + 1).toLowerCase();
        console.log(ext);
        if (allowExtension.indexOf(ext) > -1) {
          $('#src-img').attr('src', e.target.result);
        } else {
          alert('Only the following images are supported: ' + allowExtension);
        }
      };


      $('#src-img').on('load', function () {
        $('#dst-img').attr('src', gray(this));
        console.log($('#fid-image').val());
        $('.btn-download').attr('download', 'bw-' + $('#fid-image').val().replace(/\\/g,'/').replace( /.*\//, '' )).attr('href', $('#dst-img').attr('src'));
        $('.img-panel').show();
      });
      reader.readAsDataURL(input.files[0]);
    }
  }

  $('#fid-image').on('change', function() {
    readImage(this);
  });





});