<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PageCatModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\PageCatViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class PageCatController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();

    $this->_model = new model();
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_list ()
  {

    $arrFilters = array(
        'title' => Get::text('title'),
        'pag' => Get::text('pag'),
    );

    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters));
    $this->_data['search'] = vh::formatFilters($arrFilters);

    $this->setErrorSessionData();

    $this->setListTemplate('pagecat_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $excluded_fields = array(
        'cover'
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($excluded_fields);

    $this->_data['table'] = vh::formatRow($row);

    $this->setSaveTemplate('pagecat_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'cover' => Post::upload('cover'),
          'content' => Post::text('content'),
          'description' => Post::text('description'),
          'keywords' => Post::text('keywords'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
