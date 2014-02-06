<?php

namespace src\app\admin\controllers;

use src\app\admin\models\NewsModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\NewsViewHelper as vh;
use src\app\admin\models\NewsCatModel;

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

    $news_cat = new NewsCatModel;
    $news_cat_dropdown = $news_cat->getListArray();

    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters));
    $this->_data['search'] = vh::formatFilters($arrFilters, $news_cat_dropdown);

    $this->setErrorSessionData();

    $this->setListTemplate('news_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $excluded_fields = array(
        'cover'
    );

    $row = $id ? $this->_model->getById() : $this->getPrevious($excluded_fields);

    $news_cat = new NewsCatModel;
    $news_cat_dropdown = $news_cat->getListArray();

    $listbox = array(
        'photo_values' => $this->_model->arrayRelationshipPhoto(),
        'photo_selected' => $this->_model->selectedRelationshipPhoto(),
        'video' => $this->_model->arrayRelationshipVideo()
    );

    $this->_data['table'] = vh::formatRow($row, $news_cat_dropdown, $listbox);

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
          'cover' => Post::upload('cover'),
          'r_news_photo' => Post::aray('r_news_photo'),
          'r_news_video' => Post::aray('r_news_video'),
          'photo' => Post::text('photo'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

  public function post_r_news_video ()
  {
    $r = $this->_model->ajaxRelationshipVideo(Post::text('term'));
    die($r);
  }

}
