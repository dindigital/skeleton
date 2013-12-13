<?

require $_SERVER['DOCUMENT_ROOT'] . '/lib/Form/Browser/CKFinder/CKFinder.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/Session/iSession.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/Session/Session.php';


$ckfinder1 = new \lib\Form\Browser\CKFinder\CKFinder('upload1');
$ckfinder1->setStartUpPath('Videos:/');
$campo1 = $ckfinder1->getElement();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>CKFinder - Sample - CKEditor Integration</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="/adm/scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/adm/plugins/ckfinder23/ckfinder.js"></script>
    <script type="text/javascript" src="/adm/scripts/ckfinder.js"></script>
  </head>
  <body>
    <?=$campo1?>
  </body>
</html>
