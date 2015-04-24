<?php

namespace Site\Models\Log;

use Monolog\Logger;
use Site\Models\Log\LoggerDecorator;
use Monolog\Handler\StreamHandler;

class Log
{

  protected static $_site;

  protected static function build ()
  {
    if ( is_null(self::$_site) ) {

      $txt_handler = new StreamHandler(MONOLOG);
      //$mail_handler...

      $site = new Logger('site');
      $site->pushHandler($txt_handler);

      self::$_site = new LoggerDecorator($site);
    }

  }

  /**
   *
   * @return LoggerDecorator
   */
  public static function getSite ()
  {
    self::build();
    return self::$_site;

  }

}
