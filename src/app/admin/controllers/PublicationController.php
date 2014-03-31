<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PublicationModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class PublicationController extends BaseControllerAdm
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
        'pag' => Get::text('pag')
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('publication_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $excluded_fields = array(
        'file'
    );

    $this->_data['table'] = $id ? $this->_model->getRow() : $this->getPrevious($excluded_fields);

    $this->setSaveTemplate('publication_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'uri' => Post::text('uri'),
          'file' => Post::upload('file'),
          'file_delete' => Post::checkbox('file_delete'),
          'publish_issuu' => Post::checkbox('publish_issuu'),
          'republish_issuu' => Post::checkbox('republish_issuu'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
