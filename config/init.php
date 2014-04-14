<?php

function errHandle ( $errNo, $errStr, $errFile, $errLine )
{
  if ( error_reporting() == 0 ) {
// @ suppression used, don't worry about it
    return;
  }

  $msg = "$errStr in $errFile on line $errLine";
  throw new ErrorException($msg, $errNo);

}

set_error_handler('errHandle');

if ( !ini_get('safe_mode') ) {
  ini_set('max_execution_time', 0);
  ini_set('max_input_time', 0);
  ini_set('session.gc_probability', 0);
  ini_set('session.gc_divisor', 100);
  ini_set('session.gc_maxlifetime', 60 * 30);
  ini_set('display_erros', 'On');
  ini_set('error_reporting', E_ALL ^ E_DEPRECATED);
  ini_set('default_charset', 'UTF-8');
}

chdir('../');
