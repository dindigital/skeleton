<?php

namespace src\app\admin\controllers;

use src\app\admin\models\AdminModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\PermissionModel;
use src\app\admin\viewhelpers\AdminViewHelper as vh;

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

    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters));
    $this->_data['search'] = vh::formatFilters($arrFilters);

    $this->setErrorSessionData();

    $this->setListTemplate('admin_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $exclude_previous = array(
        'avatar',
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($exclude_previous);

    $permission = new PermissionModel;
    $permission_listbox = $permission->getArrayList(@$this->_data['table']['permission']);

    $this->_data['table'] = vh::formatRow($row, $permission_listbox);

    $this->setSaveTemplate('admin_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $info = array(
          'active' => Post::checkbox('active'),
          'name' => Post::text('name'),
          'email' => Post::text('email'),
          'password' => Post::text('password'),
          'avatar' => Post::upload('avatar'),
          'permission' => Post::aray('permission'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
