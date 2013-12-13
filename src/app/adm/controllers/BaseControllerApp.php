<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;

abstract class BaseControllerApp extends BaseController
{

  public function __construct ()
  {
    parent::__construct();

    $this->_data = array(
        'assets' => $this->getAssets(),
    );

    $this->_view->addFile('src/app/adm/views/layout.phtml');
    $this->_view->addFile('src/app/adm/views/includes/head.php', '{$HEAD}');
    $this->_view->addFile('src/app/adm/views/includes/side_bar.php', '{$SIDE_BAR}');
    $this->_view->addFile('src/app/adm/views/includes/footer.php', '{$FOOTER}');
  }

}
