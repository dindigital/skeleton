<?php

namespace src\app\admin\controllers\essential;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\essential\AdminAuthModel;
use Exception;
use Din\Session\Session;
use Din\Image\Picuri;
use Din\Http\Get;
use Din\Http\Post;
use Din\Http\Header;
use src\app\admin\helpers\Entities;
use src\app\admin\models\essential\PermissionModel;
use Din\ViewHelpers\JsonViewHelper;
use src\app\admin\models\essential\TrashModel;
use src\app\admin\models\essential\RelationshipModel;

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

  protected function setSaveTemplate ( $filename )
  {
    $this->setSavedMsgData();

    $this->_view->addFile('src/app/admin/views/includes/alerts.phtml', '{$ALERTS}');
    $this->_view->addFile('src/app/admin/views/includes/submit.phtml', '{$SUBMIT}');
    $this->_view->addFile('src/app/admin/views/includes/uri.phtml', '{$URI}');

    $this->_view->addFile('src/app/admin/views/' . $filename, '{$CONTENT}');
    $this->display_html();
  }

  protected function setListTemplate ( $filename )
  {
    $this->setSavedMsgData();

    $paginator = $this->_model->getPaginator();

    $this->_data['paginator']['itens_per_page'] = $paginator->getItensPerPage();
    $this->_data['paginator']['offset'] = $paginator->getOffset() + 1;
    $this->_data['paginator']['total'] = $paginator->getTotal();
    $this->_data['paginator']['numbers'] = $paginator->getNumbers();

    $this->_view->addFile('src/app/admin/views/includes/alert_lista.phtml', '{$ALERT}');
    $this->_view->addFile('src/app/admin/views/includes/pagination.phtml', '{$PAGINATION}');
    $this->_view->addFile('src/app/admin/views/includes/list_header_btn.phtml', '{$LIST_HEADER_BTN}');

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
    $adminAuthModel = new AdminAuthModel;
    if ( !$adminAuthModel->is_logged() )
      Header::redirect('/admin/');

    $this->_data['admin'] = $adminAuthModel->getUser();
    $this->_data['admin']['avatar_img'] = Picuri::picUri($this->_data['admin']['avatar'], 30, 30, true);

    $permission = new PermissionModel;
    $permissions = $permission->getByAdmin($this->_data['admin']);
    $this->_data['permission'] = array_fill_keys($permissions, '');
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
    $this->_data['entity'] = Entities::getThis($this->_model);
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

    $entity = Entities::getThis($this->_model);

    $redirect = '/admin/' . $entity['tbl'] . '/save/' . $id . '/';
    if ( Post::text('redirect') == 'list' ) {
      $redirect = '/admin/' . $entity['tbl'] . '/list/';
    }

    if ( Post::text('redirect') == 'previous' ) {
      $session = new Session('adm_session');
      $session->set('previous_id', $id);
      $redirect = '/admin/' . $entity['tbl'] . '/save/';
    }

    JsonViewHelper::redirect($redirect);
  }

  protected function getPrevious ( $exclude = array() )
  {
    $session = new Session('adm_session');

    if ( $session->is_set('previous_id') ) {
      $this->_model->setId($session->get('previous_id'));
      $row = $this->_model->getById($session->get('previous_id'));

      foreach ( $exclude as $field ) {
        $row[$field] = null;
      }

      $session->un_set('previous_id');
    } else {
      $row = $this->_model->getNew();
    }


    return $row;
  }

  public function post_delete ()
  {
    try {
      $itens = Post::aray('itens');

      $entity = Entities::getThis($this->_model);
      if ( isset($entity['trash']) && $entity['trash'] ) {
        $trash = new TrashModel();
        $trash->delete($itens);
      } else {
        $this->_model->delete($itens);
      }

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

  public function post_active ()
  {
    $this->_model->toggleActive(Post::text('id'), Post::checkbox('active'));
  }

  public function post_sequence ()
  {
    try {
      $this->_model->changeSequence(Post::text('id'), Post::text('sequence'));

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

  public function get_ajax_relationship ()
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setRelationshipSection(Get::text('relationshipSection'));
    $result = $relationshipModel->getAjax(Get::text('q'));
    die($result);
  }

  public function post_ajax_relationship ()
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentSection(Post::text('currentSection'));
    $relationshipModel->setRelationshipSection(Post::text('relationshipSection'));
    $result = $relationshipModel->getAjaxCurrent(Post::text('id'));
    die($result);
  }

}
