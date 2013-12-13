<?

function uploadify ( $name )
{
  $upload = <<<EOD
<input id="{$name}" name="{$name}" type="file" multiple="true" class="uploadify">
<script type="text/javascript">
		$(function() {
			$('#{$name}').uploadify({
				'formData'     : {
				},
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php',
				'auto' : false,
        'onQueueComplete': teste
			});
		});
	</script>  
EOD;

  return $upload;
}
?><!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>UploadiFive Test</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="jquery.uploadify.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="uploadify.css">
    <style type="text/css">
      body {
        font: 13px Arial, Helvetica, Sans-serif;
      }
    </style>
    <script>
      var totalUploadFields = 0;
      var teste = function(){
        // When all files are uploaded submit form
        console.log('CONSEGUI BINDAR!');
        totalUploadFields -= 1;
        $('form').submit();
      }      
      $(function(){
        
        totalUploadFields = $('.uploadify').length;
        console.log(totalUploadFields);
        // Client side form validation
        $('form').submit(function(e) {
          
          if (totalUploadFields == 0){
            return true
          }
          console.log(totalUploadFields);
          $('.uploadify').each(function(){
            var uploader = $(this);

            // Files in queue upload them first
            if (uploader.data('uploadify').queueData.queueLength > 0) {


              uploader.uploadify('upload','*');
              return false;
            } else if (uploader.data('uploadify').queueData.uploadsSuccessful == 0){
              alert('Por favor selecionar um arquivo no campo de upload');
              return false;
            }
          
          });

          return false;
          
        });
        
        
      });
    </script>
  </head>

  <body>
    <h1>Uploadify Demo</h1>
    <form method="post">
      <?= uploadify('arquivo1') ?>
      <?= uploadify('arquivo2') ?>
      <input type="submit" />
    </form>


  </body>
</html>