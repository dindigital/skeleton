<?php

namespace src\app\admin\controllers;

use src\app\admin\models\SurveyModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class SurveyController extends BaseControllerAdm
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

    $this->setListTemplate('survey_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);
    $excluded_fields = array(
        'id_survey',
        'uri'
    );

    $this->_data['table'] = $id ? $this->_model->getById() : $this->getPrevious($excluded_fields);

    $this->setSaveTemplate('survey_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'uri' => Post::text('uri'),
          'question' => Post::aray('question'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
