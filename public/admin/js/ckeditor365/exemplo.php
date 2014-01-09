<?


if (count($_POST)){
  var_dump($_POST);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type" />
    <script type="text/javascript" src="/backend/scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/backend/plugins/ckeditor365/ckeditor.js"></script>
    <script>
      $(document).ready(function(){
        $('div').append('<textarea class="ckeditor" name="editor3"></textarea>');
        $('div').append('<textarea class="ckeditor" name="editor43"></textarea>');
      });
    </script>
  </head>
  <body>
    <form method="post">
      <div>&nbsp;</div>
      <input type="submit" />
    </form>
  </body>
</html>
