<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PageModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

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

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('page_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $exclude_previous = array(
        'cover',
        'uri'
    );

    $this->_data['table'] = $id ? $this->_model->getRow() : $this->getPrevious($exclude_previous);

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
          'cover_delete' => Post::checkbox('cover_delete'),
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

  public function get_ajax_intinify_cat ()
  {
    $id_page_cat = Get::text('id_page_cat');
    $exclude_id = Get::text('exclude_id');

    $dropdown = $this->_model->getListArray($id_page_cat, null, $exclude_id);
    die($this->_model->formatInfiniteDropdown($dropdown));
  }

  public function get_ajax_infinity ()
  {
    $id_parent = Get::text('id_parent');
    $exclude_id = Get::text('exclude_id');

    $dropdown = $this->_model->getListArray(null, $id_parent, $exclude_id);
    die($this->_model->formatInfiniteDropdown($dropdown));
  }

}
