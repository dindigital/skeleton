<?
$is_pjax = $_SERVER['HTTP_X_PJAX'] == 'true';

/*if ( $is_pjax )
  echo 'PJAX !!!';
else
  echo 'diretão... chatão...';*/
/* var_dump(apache_request_headers());
  var_dump(getallheaders()); */

$error = '';
$exec = '';
$link = (intval($_GET['page'])+1);
$content = <<<EOD
  <p>
    <input type="button" onclick="history.back()" value="voltar" />
    aLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec metus neque, rutrum ut varius eget, mattis vel tortor. Suspendisse eleifend nunc ut felis mollis non pulvinar sem eleifend. Sed ut elit magna, vel ullamcorper dolor. Donec tristique ullamcorper risus. Nam dictum lacinia mi, luctus blandit ligula eleifend id. Morbi nec mi justo, in dictum orci. Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed in risus velit, in consequat turpis. Suspendisse quis mollis dolor.
  </p>
  <a href='conteudo.php?page={$link}' class="replace_container">Explore  {$_GET['page']}</a>
EOD;

//header('Content-type: application/json; charset=UTF-8');
$r = array('error'=>$error, 'exec'=>$exec, 'content'=> $content);
die (json_encode($r));



exit;