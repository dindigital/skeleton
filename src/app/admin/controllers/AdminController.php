<?php

namespace src\app\admin\controllers;

use src\app\admin\models\AdminModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class AdminController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_list ()
  {
    $arrFilters = array(
        'name' => Get::text('name'),
        'email' => Get::text('email'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('admin_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);
    $exclude_previous = array(
        'avatar'
    );
    $this->_data['table'] = $id ? $this->_model->getRow() : $this->getPrevious($exclude_previous);

    $this->setSaveTemplate('admin_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'name' => Post::text('name'),
          'email' => Post::text('email'),
          'password' => Post::text('password'),
          'avatar' => Post::upload('avatar'),
          'permission' => Post::aray('permission'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
