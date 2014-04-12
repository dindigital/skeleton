<?php

namespace src\app\admin\controllers;

use src\app\admin\models\MailingGroupModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class MailingGroupSaveController extends BaseControllerAdm
{

  protected $_model;
  protected $_id;

  public function __construct ( $id )
  {
    $this->_id = $id;
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();

  }

  public function get ()
  {
    $this->defaultSavePage('mailing_group_save.phtml', $this->_id);

  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'name' => Post::text('name'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

  }

}
