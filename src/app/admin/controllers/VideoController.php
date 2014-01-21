<?php

namespace src\app\admin\controllers;

use src\app\admin\models\VideoModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\VideoViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class VideoController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model();
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_lista ()
  {
    $arrFilters = array(
        'titulo' => Get::text('titulo'),
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->listar($arrFilters, $paginator));
    $this->_data['busca'] = vh::formatFilters($arrFilters);

    $this->setErrorSessionData();

    $this->setListTemplate('video_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    $row = $id ? $this->_model->getById($id) : $this->getPrevious();
    $this->_data['table'] = vh::formatRow($row);

    $this->setCadastroTemplate('video_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'titulo' => Post::text('titulo'),
          'data' => Post::text('data'),
          'descricao' => Post::text('descricao'),
          'link_youtube' => Post::text('link_youtube'),
          'link_vimeo' => Post::text('link_vimeo'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
