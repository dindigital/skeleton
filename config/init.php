<?php

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

chdir($_SERVER['DOCUMENT_ROOT']);

spl_autoload_register(function($namespaced_class) {
  if ( strpos($namespaced_class, 'src') !== 0 )
    return;

  $path = str_replace('\\', DIRECTORY_SEPARATOR, $namespaced_class);
  $path .= '.php';

  if ( strpos($namespaced_class, '\\') === false && !is_file($path) )
    return;

  if ( !is_file($path) )
    throw new Exception('Autoload Error: ' . $path);

  require_once $path;
});
