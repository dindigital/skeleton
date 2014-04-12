<?php

namespace Admin\Controllers;

use Din\Mvc\Controller\BaseController;
use Admin\Models\Essential\AdminAuthModel as model;
use Din\Http\Header;
use Din\Http\Post;
use Exception;
use Helpers\JsonViewHelper;
use Din\Session\Session;
use Din\Assets\AssetsConfig;
use Din\Assets\AssetsRead;

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
    $config = new AssetsConfig('config/assets.php');
    $assetsRead = new AssetsRead($config);
    $assetsRead->setMode(ASSETS);
    $assetsRead->setReplace(PATH_REPLACE);
    $assetsRead->setGroup('css', array('css_admlogin', 'css_admgoogle'));
    $assetsRead->setGroup('js', array('js_admjquery', 'js_admlogin'));
    $this->_data['assets'] = $assetsRead->getAssets();

    $session = new Session('adm_session');
    if ( $session->is_set('saved_msg') ) {
      $this->_data['saved_msg'] = $session->get('saved_msg');
    }
    $session->un_set('saved_msg');

    $this->_view->addFile('src/app/Admin/Views/layouts/login.phtml');

  }

  public function get ()
  {
    $this->setAuthTemplate();
    $this->_view->addFile('src/app/Admin/Views/Essential/login.phtml', '{$CONTENT}');
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
