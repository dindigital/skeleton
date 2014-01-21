<?php

namespace src\app\admin\controllers\essential;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\essential\UsuarioAuthModel;
use Exception;
use Din\Session\Session;
use Din\Image\Picuri;
use Din\Http\Post;
use Din\Http\Header;
use src\app\admin\helpers\Entities;
use src\app\admin\models\essential\PermissaoModel;
use Din\ViewHelpers\JsonViewHelper;

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

  /**
   * Verifica se usuário está logado e seta os dados de usuário
   * @throws Exception - Caso o usuário não esteja logado
   */
  private function setUserData ()
  {
    $usuarioAuthModel = new UsuarioAuthModel();
    if ( !$usuarioAuthModel->is_logged() )
    //throw new Exception('Permissão negada, usuário deve estar logado.');
      Header::redirect('/admin/');

    $this->_data['user'] = $usuarioAuthModel->getUser();
    $this->_data['user']['avatar_img'] = Picuri::picUri($this->_data['user']['avatar'], 30, 30, true);

    $permissao = new PermissaoModel();
    $permissoes = $permissao->getArray($this->_data['user']);
    $this->_data['permissao'] = array_fill_keys($permissoes, '');
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

  protected function setEntityData ()
  {
    $this->_data['entity'] = Entities::getThis($this->_model);
  }

  public function require_permission ()
  {
    $permissao = new PermissaoModel();
    $permissao->block($this->_model, $this->_data['user']);
  }

  public function saveAndRedirect ( $info, $id = null )
  {
    if ( !$id ) {
      $id = $this->_model->inserir($info);
    } else {
      $this->_model->atualizar($id, $info);
    }

    $this->setRegistroSalvoSession();

    $entity = Entities::getThis($this->_model);

    $redirect = '/admin/' . $entity['tbl'] . '/cadastro/' . $id . '/';
    if ( Post::text('redirect') == 'lista' ) {
      $redirect = '/admin/' . $entity['tbl'] . '/lista/';
    }

    if ( Post::text('redirect') == 'previous' ) {
      $session = new Session('adm_session');
      $session->set('previous_id', $id);
      $redirect = '/admin/' . $entity['tbl'] . '/cadastro/';
    }

    JsonViewHelper::redirect($redirect);
  }

  protected function getPrevious ( $exclude = array() )
  {
    $session = new Session('adm_session');
    $row = array();
    if ( $session->is_set('previous_id') ) {
      $row = $this->_model->getById($session->get('previous_id'));

      foreach ( $exclude as $field ) {
        unset($row[$field]);
      }
    }

    $session->un_set('previous_id');
    return $row;
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

  public function post_ordem ()
  {
    try {
      $this->_model->changeOrdem(Post::text('id'), Post::text('ordem'));

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}
