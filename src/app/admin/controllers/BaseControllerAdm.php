<?php

namespace src\app\admin\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\UsuarioModel;
use src\app\admin\models\UsuarioAuthModel;
use \Exception;
use Din\Session\Session;
use Din\Image\Picuri;
use Din\Http\Post;
use Din\Http\Header;
use Din\Filters\Date\DateFormat;

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
    $this->setBasicTemplate();
    $this->setViewHelpers();
    $this->setDefaultHeaders();
  }

  private function setDefaultHeaders ()
  {
    Header::setNoCache();
  }

  /**
   * Seta os arquivos que compõem o layout do adm
   */
  private function setBasicTemplate ()
  {
    $this->_view->addFile('src/app/admin/views/layouts/layout.phtml');
    $this->_view->addFile('src/app/admin/views/includes/nav.phtml', '{$NAV}');
  }

  public function setCadastroTemplate ( $filename )
  {
    $this->setRegistroSalvoData();

    $this->_view->addFile('src/app/admin/views/includes/alerts.phtml', '{$ALERTS}');
    $this->_view->addFile('src/app/admin/views/includes/submit.phtml', '{$SUBMIT}');

    $this->_view->addFile('src/app/admin/views/' . $filename, '{$CONTENT}');
    $this->display_html();
  }

  public function setListTemplate ( $filename, $paginator )
  {
    $this->setRegistroSalvoData();

    $this->_data['paginator']['subtotal'] = $paginator->getSubTotal();
    $this->_data['paginator']['total'] = $paginator->getTotal();
    $this->_data['paginator']['numbers'] = $paginator->getNumbers();

    $this->_view->addFile('src/app/admin/views/includes/alert_lista.phtml', '{$ALERT}');
    $this->_view->addFile('src/app/admin/views/includes/paginacao.phtml', '{$PAGINACAO}');
    $this->_view->addFile('src/app/admin/views/includes/btns_lista_cad-exc.phtml', '{$BTN_LISTA_CAD-EXC}');

    $this->_view->addFile('src/app/admin/views/' . $filename, '{$CONTENT}');
    $this->display_html();
  }

  /**
   * Seta os assets
   */
  private function setAssetsData ()
  {
    $this->_data['assets'] = $this->getAssets();
  }

  public function setViewHelpers ()
  {
    $this->_data['DateFormat'] = new DateFormat;
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
    $this->_data['user']['avatar_img'] = Picuri::picUri($this->_data['user']['avatar'], 30, 30, true);
  }

  protected function setRegistroSalvoSession ()
  {
    $session = new Session('adm_session');
    $session->set('registro_salvo', 'Registro salvo com sucesso!');
  }

  protected function setRegistroSalvoData ()
  {
    $session = new Session('adm_session');
    if ( $session->is_set('registro_salvo') ) {
      $this->_data['registro_salvo'] = $session->get('registro_salvo');
    }
    $session->un_set('registro_salvo');
  }

  protected function setErrorSession ( $msg )
  {
    $session = new Session('adm_session');
    $session->set('error', $msg);

    Header::redirect(Header::getReferer());
  }

  protected function setErrorSessionData ()
  {
    $session = new Session('adm_session');
    if ( $session->is_set('error') ) {
      $this->_data['error'] = $session->get('error');
    }
    $session->un_set('error');
  }

  //_# OPERAÇÕES COMUNS
  public function post_excluir ()
  {
    try {
      $itens = Post::aray('itens');

      foreach ( $itens as $item ) {
        list($tbl, $id) = explode('_', $item);
        $model_name = "\\src\\app\\admin\\models\\{$tbl}Model";
        $model = new $model_name;
        $model->excluir($id);
      }

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

  public function post_ativo ()
  {
    $this->_model->toggleAtivo(Post::text('id'), Post::checkbox('ativo'));
  }

}
