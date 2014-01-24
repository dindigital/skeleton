<?php

namespace src\app\admin\controllers;

use src\app\admin\models\NoticiaModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\NoticiaViewHelper as vh;
use src\app\admin\models\NoticiaCatModel;
use src\app\admin\models\FotoModel;
use src\app\admin\models\VideoModel;

/**
 *
 * @package app.controllers
 */
class NoticiaController extends BaseControllerAdm
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
        'id_noticia_cat' => Get::text('id_noticia_cat'),
    );

    $noticia_cat = new NoticiaCatModel();
    $noticia_cat_dropdown = $noticia_cat->getListArray();

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->listar($arrFilters, $paginator));
    $this->_data['busca'] = vh::formatFilters($arrFilters, $noticia_cat_dropdown);

    $this->setErrorSessionData();

    $this->setListTemplate('noticia_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    $excluded_fields = array(
        'capa'
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($excluded_fields);

    $noticia_cat = new NoticiaCatModel();
    $noticia_cat_dropdown = $noticia_cat->getListArray();

    $listbox = array(
        'foto_values' => $this->_model->arrayRelationshipFoto(),
        'foto_selected' => $this->_model->selectedRelationshipFoto($id),
        'video' => $this->_model->arrayRelationshipVideo($id)
    );

    $this->_data['table'] = vh::formatRow($row, $noticia_cat_dropdown, $listbox);

    $this->setCadastroTemplate('noticia_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'id_noticia_cat' => Post::text('id_noticia_cat'),
          'titulo' => Post::text('titulo'),
          'data' => Post::text('data'),
          'chamada' => Post::text('chamada'),
          'corpo' => Post::text('corpo'),
          'capa' => Post::upload('capa'),
          'r_noticia_foto' => Post::aray('r_noticia_foto'),
          'r_noticia_video' => Post::aray('r_noticia_video'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

  public function post_r_noticia_video ()
  {
    $r = $this->_model->ajaxRelationshipVideo(Post::text('term'));
    die($r);
  }

}

