<?php

namespace Admin\Controllers;

use Din\Mvc\Controller\BaseController;
use Admin\Models\Essential\AdminPasswordModel as model;
use Din\Http\Post;
use Exception;
use Helpers\JsonViewHelper;

/**
 *
 * @package app.controllers
 */
class AdminPasswordRecoverController extends BaseController
{

  private $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;

  }

  public function post ()
  {
    $input = array(
        'email' => Post::text('email')
    );

    try {
      $this->_model->recover_password($input);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    JsonViewHelper::display_success_message('E-mail enviado com sucesso, por favor acesse sua conta de e-mail para gerar uma nova senha');

  }

}
