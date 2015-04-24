<?php

namespace Admin\Controllers;

use Din\Mvc\Controller\BaseController;
use Din\Essential\Models\AdminAuthModel;
use Exception;
use Din\Session\Session;
use Din\Image\Picuri;
use Din\Http\Post;
use Din\Http\Header;
use Din\Essential\Models\PermissionModel;
use Helpers\JsonViewHelper;
use Din\Http\Get;

/**
 * Classe abstrata que será a base de todos os controllers do adm
 */
abstract class BaseControllerAdm extends BaseController
{

  protected $_entity;

  public function __construct ()
  {
    parent::__construct();

    $this->setUserData();
    $this->setDefaultHeaders();

  }

  protected function setDefaultHeaders ()
  {
    Header::setNoCache();

  }

  /**
   * Seta os arquivos que compõem o layout do adm
   */
  protected function setBasicTemplate ()
  {
    $this->_view->addFile('src/app/Admin/Views/layouts/layout.phtml');
    $this->_view->addFile('src/app/Admin/Views/includes/nav.phtml', '{$NAV}');

  }

  protected function setSaveTemplate ( $filename )
  {
    $this->setBasicTemplate();
    $this->setSavedMsgData();

    $this->_view->addFile('src/app/Admin/Views/includes/alerts.phtml', '{$ALERTS}');
    $this->_view->addFile('src/app/Admin/Views/includes/submit.phtml', '{$SUBMIT}');
    $this->_view->addFile('src/app/Admin/Views/includes/uri.phtml', '{$URI}');
    $this->_view->addFile('src/app/Admin/Views/includes/active.phtml', '{$ACTIVE}');

    $this->_view->addFile('src/app/Admin/Views/' . $filename, '{$CONTENT}');
    $this->display_html();

  }

  protected function setListTemplate ( $filename )
  {
    $this->setBasicTemplate();
    $this->setSavedMsgData();

    $paginator = $this->_model->getPaginator();

    $this->_data['paginator']['itens_per_page'] = $paginator->getItensPerPage();
    $this->_data['paginator']['offset'] = $paginator->getOffset() + 1;
    $this->_data['paginator']['total'] = $paginator->getTotal();
    $this->_data['paginator']['numbers'] = $paginator->getNumbers();

    $this->_view->addFile('src/app/Admin/Views/includes/alert_lista.phtml', '{$ALERT}');
    $this->_view->addFile('src/app/Admin/Views/includes/pagination.phtml', '{$PAGINATION}');
    $this->_view->addFile('src/app/Admin/Views/includes/list_header_btn.phtml', '{$LIST_HEADER_BTN}');

    $this->_view->addFile('src/app/Admin/Views/' . $filename, '{$CONTENT}');
    $this->display_html();

  }

  /**
   * Verifica se usuário está logado e seta os dados de usuário
   * @throws Exception - Caso o usuário não esteja logado
   */
  protected function setUserData ()
  {
    $adminAuthModel = new AdminAuthModel;
    if ( !$adminAuthModel->is_logged() )
      Header::redirect('/admin/');

    $this->_data['admin'] = $adminAuthModel->getUser();
    $this->_data['admin']['avatar_img'] = Picuri::picUri($this->_data['admin']['avatar'], 30, 30, true);

    $permission = new PermissionModel;
    $this->_data['menu'] = $permission->getMenu($this->_data['admin']);

  }

  protected function setSavedMsgSession ()
  {
    $session = new Session('adm_session');
    $session->set('saved_msg', 'Registro salvo com sucesso!');

  }

  protected function setSavedMsgData ()
  {
    $session = new Session('adm_session');
    if ( $session->is_set('saved_msg') ) {
      $this->_data['saved_msg'] = $session->get('saved_msg');
    }
    $session->un_set('saved_msg');

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
    $this->_entity = $this->_model->_entity;

    $this->_data['entity'] = array(
        'tbl' => $this->_entity->getTbl(),
        'id' => $this->_entity->getId(),
        'section' => $this->_entity->getSection()
    );

  }

  protected function require_permission ()
  {
    $permission = new PermissionModel;
    $permission->block($this->_model, $this->_data['admin']);

  }

  protected function saveAndRedirect ( $info )
  {
    $id = $this->_model->save($info);

    $this->setSavedMsgSession();

    $entity = $this->_model->_entity;

    $redirect = '/admin/' . $entity->getTbl() . '/save/' . $id . '/';
    if ( Post::text('redirect') == 'list' ) {
      $redirect = '/admin/' . $entity->getTbl() . '/';
    }

    if ( Post::text('redirect') == 'previous' ) {
      $redirect = '/admin/' . $entity->getTbl() . '/save/?using_record=' . $id;
    }

    JsonViewHelper::redirect($redirect);

  }

  protected function defaultSavePage ( $filename, $id )
  {
    $this->_model->setId($id);
    $template_id = Get::text('using_record');

    if ( $id ) {
      $this->_data['table'] = $this->_model->getRow();
    } else if ( $template_id ) {
      $this->_data['table'] = $this->_model->getNewUsingRecord($template_id);
    } else {
      $this->_data['table'] = $this->_model->getNew();
    }

    $this->setSaveTemplate($filename);

  }

}
