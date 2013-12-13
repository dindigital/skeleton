<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\adm\models\UsuarioModel;
use src\app\adm\models\UsuarioLoginModel;

abstract class BaseControllerApp extends BaseController
{

  public function __construct ()
  {
    parent::__construct();

    $this->setAssetsData();
    $this->setUserData();
    $this->setViewFiles();
  }

  private function setViewFiles ()
  {
    $this->_view->addFile('src/app/adm/views/layout.phtml');
    $this->_view->addFile('src/app/adm/views/includes/head.php', '{$HEAD}');
    $this->_view->addFile('src/app/adm/views/includes/side_bar.php', '{$SIDE_BAR}');
    $this->_view->addFile('src/app/adm/views/includes/footer.php', '{$FOOTER}');
  }

  private function setAssetsData ()
  {
    $this->_data['assets'] = $this->getAssets();
  }

  private function setUserData ()
  {
    $usuariologinmodel = new UsuarioLoginModel;
    if ( !$usuariologinmodel->is_logged() )
      throw new \Exception('Permissão negada, usuário deve estar logado.');

    $usuario = new UsuarioModel();
    $this->_data['user'] = $usuario->getById($usuariologinmodel->getId());
    $this->_data['user']['avatar'] = '<img src="/adm/images/profile.png" />';
  }

}
