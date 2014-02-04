<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PageModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\PageCatModel;
use src\app\admin\viewhelpers\PageViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class PageController extends BaseControllerAdm
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
        'title' => Get::text('title'),
        'id_page_cat' => Get::text('id_page_cat'),
        'pag' => Get::text('pag'),
    );

    $pagina_cat = new PageCatModel;
    $pagina_cat_dropdown = $pagina_cat->getListArray();

    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters));
    $this->_data['search'] = vh::formatFilters($arrFilters, $pagina_cat_dropdown);

    $this->setErrorSessionData();

    $this->setListTemplate('page_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $exclude_previous = array(
        'cover'
    );
    $row = $id ? $this->_model->getById() : $this->getPrevious($exclude_previous);

    $pagina_cat = new PageCatModel;
    $pagina_cat_dropdown = $pagina_cat->getListArray();

    $this->_data['table'] = vh::formatRow($row, $pagina_cat_dropdown);

    $this->setSaveTemplate('page_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'id_page_cat' => Post::text('id_page_cat'),
          'id_parent' => Post::aray('id_parent'),
          'title' => Post::text('title'),
          'cover' => Post::upload('cover'),
          'content' => Post::text('content'),
          'description' => Post::text('description'),
          'keywords' => Post::text('keywords'),
          'uri' => Post::text('uri'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

  public function get_ajax_intinify_cat ( $id_pagina_cat )
  {
    $dorpdown = $this->_model->getListArray($id_pagina_cat, null);
    die(vh::formatInfiniteDropdown($dorpdown));
  }

  public function get_ajax_infinity ( $id_parent )
  {
    $dorpdown = $this->_model->getListArray(null, $id_parent);
    die(vh::formatInfiniteDropdown($dorpdown));
  }

}
