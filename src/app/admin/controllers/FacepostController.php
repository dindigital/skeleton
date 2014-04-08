<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\FacepostModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use Din\Http\Header;
use Din\Session\Session;

/**
 *
 * @package app.controllers
 */
class FacepostController extends BaseControllerAdm
{

  protected $_model;

  protected function setModel ( $section, $id )
  {
    $this->_model = new model($section, $id);
  }

  public function get_edit ( $section, $id )
  {
    $session = new Session('adm_session');
    $session->set('referer', Header::getReferer());

    $this->setModel($section, $id);

    $fb_login = $this->_model->getFacebookLogin();
    if ( $fb_login ) {
      Header::redirect($fb_login);
    }

    $session->set('saved_msg', 'Atenticado no Facebook com sucesso');

    $this->_data['redirect'] = $session->get('referer');
    $this->_data['post'] = $this->_model->generatePost();

    $this->setSaveTemplate('essential/facepost_edit.phtml');
  }

  public function post_edit ( $section, $id )
  {
    try {

      $this->setModel($section, $id);
      $this->_model->post(array(
          'name' => Post::text('name'),
          'link' => Post::text('link'),
          'picture' => Post::text('picture'),
          'description' => Post::text('description'),
          'message' => Post::text('message'),
      ));

      $session = new Session('adm_session');
      $session->set('saved_msg', 'Registro postado com sucesso!');

      JsonViewHelper::redirect(Post::text('redirect'));
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

  public function get_view ( $section, $id )
  {
    $this->setModel($section, $id);

    $this->_data['redirect'] = Header::getReferer();
    $this->_data['posts'] = $this->_model->getPosts();

    $this->setSaveTemplate('essential/facepost_view.phtml');
  }

}
