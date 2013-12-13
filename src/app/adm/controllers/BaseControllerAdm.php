<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\adm\models\UsuarioModel;
use src\app\adm\models\UsuarioAuthModel;
use \Exception;

/**
 * Classe abstrata que será a base de todos os controllers do adm
 */
abstract class BaseControllerAdm extends BaseController
{

  public function __construct ()
  {
    parent::__construct();

    $this->setAssetsData();
    $this->setUserData();
    $this->setViewFiles();
  }

  /**
   * Seta os arquivos que compões o layout do adm
   */
  private function setViewFiles ()
  {
    $this->_view->addFile('src/app/adm/views/layout.phtml');
    $this->_view->addFile('src/app/adm/views/includes/head.php', '{$HEAD}');
    $this->_view->addFile('src/app/adm/views/includes/side_bar.php', '{$SIDE_BAR}');
    $this->_view->addFile('src/app/adm/views/includes/footer.php', '{$FOOTER}');
  }

  /**
   * Seta os assets
   */
  private function setAssetsData ()
  {
    $this->_data['assets'] = $this->getAssets();
  }

  /**
   * Verifica se usuário está logado e seta os dados de usuário
   * @throws Exception - Caso o usuário não esteja logado
   */
  private function setUserData ()
  {
    $usuarioAuthModel = new UsuarioAuthModel();
    if ( !$usuarioAuthModel->is_logged() )
      throw new Exception('Permissão negada, usuário deve estar logado.');

    $usuario = new UsuarioModel();
    $this->_data['user'] = $usuario->getById($usuarioAuthModel->getId());
    $this->_data['user']['avatar'] = '<img src="/adm/images/profile.png" />';
  }

}
