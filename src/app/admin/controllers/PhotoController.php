<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PhotoModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class PhotoController extends BaseControllerAdm
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
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('photo_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $excluded_fields = array(
        'gallery',
        'uri'
    );
    $this->_data['table'] = $id ? $this->_model->getRow() : $this->getPrevious($excluded_fields);

    $this->setSaveTemplate('photo_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'date' => Post::text('date'),
          'gallery_uploader' => Post::upload('gallery_uploader'),
          'sequence' => Post::text('gallery_sequence'),
          'label' => Post::aray('label'),
          'credit' => Post::aray('credit'),
          'uri' => Post::text('uri'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
