<?php

namespace Site\Models\Log;

use Monolog\Logger;

class LoggerDecorator
{

  protected $_logger;

  /**
   *
   * @param \Monolog\Logger $logger
   */
  public function __construct ( Logger $logger )
  {
    $this->_logger = $logger;

  }

  public function addDebug ( $message )
  {
    return $this->_logger->addDebug($message);

  }

  public function addInfo ( $message, $addcontext = array() )
  {
    return $this->_logger->addInfo($message, $this->getContext($addcontext));

  }

  public function addWarning ( $message, $addcontext = array() )
  {
    return $this->_logger->addWarning($message, $this->getContext($addcontext));

  }

  public function addError ( $message, $addcontext = array() )
  {
    return $this->_logger->addError($message, $this->getContext($addcontext));

  }

  public function addCritical ( $message, $addcontext = array() )
  {
    return $this->_logger->addCritical($message, $this->getContext($addcontext));

  }

  public function addEmergency ( $message, $addcontext = array() )
  {
    return $this->_logger->addEmergency($message, $this->getContext($addcontext));

  }

  public function addAlert ( $message, $addcontext = array() )
  {
    return $this->_logger->addAlert($message, $this->getContext($addcontext));

  }

  protected function getContext ( $addcontext = array() )
  {
    $debug_backtrace = debug_backtrace();
    $class = $debug_backtrace[2]['class'];
    $line = $debug_backtrace[1]['line'];
    $ip = $this->getIp();

    $context = array(
        'class' => $class,
        'line' => $line,
        'ip' => $ip,
    );

    return array_merge($context, $addcontext);

  }

  protected function getIp ()
  {
    if ( !empty($_SERVER['HTTP_CLIENT_IP']) )
      return $_SERVER['HTTP_CLIENT_IP'];
    elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) )
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    else
      return $_SERVER['REMOTE_ADDR'];

  }

}
