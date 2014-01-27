<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PhotoModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\PhotoViewHelper as vh;

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

    $this->setListTemplate('photo_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $excluded_fields = array(
        'gallery'
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($excluded_fields);

    $this->_data['table'] = vh::formatRow($row);

    $this->setSaveTemplate('photo_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'date' => Post::text('date'),
          'gallery_uploader' => Post::upload('gallery_uploader'),
          'sequence' => Post::text('gallery_sequence'),
          'label' => Post::aray('label'),
          'credit' => Post::aray('credit'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
