<?php

namespace src\app\admin\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\essential\AdminAuthModel as model;
use Din\Http\Header;
use Din\Http\Post;
use Exception;
use Din\ViewHelpers\JsonViewHelper;
use Din\Session\Session;
use Din\AssetRead\AssetRead;

/**
 *
 * @package app.controllers
 */
class AdminAuthController extends BaseController implements \Respect\Rest\Routable
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
  }

  private function setAuthTemplate ()
  {
    $assetRead = new AssetRead('config/assets.php');
    $assetRead->setMode(ASSETS);
    $assetRead->setReplace(PATH_REPLACE);
    $assetRead->setGroup('css', array('adm_login', 'google'));
    $assetRead->setGroup('js', array('jquery', 'adm_login'));
    $this->_data['assets'] = $assetRead->getAssets();

    $session = new Session('adm_session');
    if ( $session->is_set('saved_msg') ) {
      $this->_data['saved_msg'] = $session->get('saved_msg');
    }
    $session->un_set('saved_msg');

    $this->_view->addFile('src/app/admin/views/layouts/login.phtml');
  }

  public function get ()
  {
    $this->setAuthTemplate();
    $this->_view->addFile('src/app/admin/views/essential/login.phtml', '{$CONTENT}');
    $this->display_html();
  }

  public function post ()
  {
    $email = Post::text('email');
    $password = Post::text('password');

    try {
      $this->_model->login($email, $password);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    JsonViewHelper::redirect('/admin/index/');
  }

  public function get_logout ()
  {
    $this->_model->logout();

    Header::redirect('/admin/');
  }

}
