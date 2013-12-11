<?php

namespace src\app\teste\controllers;

use Din\Mvc\Controller\BaseController;

abstract class BaseControllerApp extends BaseController
{

  public function __construct ()
  {
    parent::__construct();

    $this->_data = array(
        'assets' => $this->getAssets(),
    );

    $this->_view->addFile('src/app/teste/views/layout.phtml');
    $this->_view->addFile('src/app/teste/views/parts/head.phtml', '{$HEAD}');
  }

}
