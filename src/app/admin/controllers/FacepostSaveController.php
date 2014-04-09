<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\FacepostModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use Din\Http\Header;
use Din\Session\Session;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class FacepostSaveController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();

    $section = Get::text('section');
    $id = Get::text('id');

    $this->_model = new model($section, $id);
  }

  public function get ()
  {
    $session = new Session('adm_session');
    $session->set('referer', Header::getReferer());

    $fb_login = $this->_model->getFacebookLogin();
    if ( $fb_login ) {
      Header::redirect($fb_login);
    }

    $session->set('saved_msg', 'Atenticado no Facebook com sucesso');

    $this->_data['redirect'] = $session->get('referer');
    $this->_data['post'] = $this->_model->generatePost();

    $this->setSaveTemplate('essential/facepost_edit.phtml');
  }

  public function post ()
  {
    try {

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

}
