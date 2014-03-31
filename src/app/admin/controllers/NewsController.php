<?php

namespace src\app\admin\controllers;

use src\app\admin\models\NewsModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class NewsController extends BaseControllerAdm
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
        'id_news_cat' => Get::text('id_news_cat'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('news_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $excluded_fields = array(
        'cover',
        'uri'
    );

    $this->_data['table'] = $id ? $this->_model->getRow() : $this->getPrevious($excluded_fields);

    $this->setSaveTemplate('news_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'active' => Post::checkbox('active'),
          'id_news_cat' => Post::text('id_news_cat'),
          'title' => Post::text('title'),
          'date' => Post::text('date'),
          'head' => Post::text('head'),
          'body' => Post::text('body'),
          'uri' => Post::text('uri'),
          'cover_delete' => Post::checkbox('cover_delete'),
          'cover' => Post::upload('cover'),
          'r_news_photo' => Post::aray('r_news_photo'),
          'r_news_video' => Post::aray('r_news_video'),
          'photo' => Post::text('photo'),
          'video' => Post::text('video')
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
