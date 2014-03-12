<?php

namespace src\app\admin\controllers;

use src\app\admin\models\NewsCatModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class NewsCatController extends BaseControllerAdm
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
        'is_home' => Get::text('is_home'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('newscat_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);
    $excluded_fields = array(
        'cover',
        'uri'
    );
    $this->_data['table'] = $id ? $this->_model->getById() :
            $this->getPrevious($excluded_fields);

    $this->setSaveTemplate('newscat_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'is_home' => Post::checkbox('is_home'),
          'uri' => Post::text('uri'),
          'cover' => Post::upload('cover')
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
