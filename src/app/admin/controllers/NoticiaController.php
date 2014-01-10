<?php

namespace src\app\admin\controllers;

use src\app\admin\models\NoticiaModel;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use src\app\admin\helpers\Form;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use \Exception;
use Din\Filters\Date\DateFormat;
use src\app\admin\models\NoticiaCatModel;

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
    $this->_model = new NoticiaModel();
  }

  public function get_lista ()
  {
    $arrFilters = array(
        'titulo' => Get::text('titulo'),
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = $this->_model->listar($arrFilters, $paginator);
    $this->_data['busca'] = $arrFilters;

    $this->setErrorSessionData();

    $this->setListTemplate('noticia_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    if ( $id ) {
      $this->_data['table'] = $this->_model->getById($id);
      $this->_data['table']['data'] = DateFormat::filter_date($this->_data['table']['data']);
    } else {
      $this->_data['table'] = array();
    }

    $this->_data['table']['corpo'] = Form::Ck('corpo', @$this->_data['table']['corpo']);
    $this->_data['table']['capa'] = Form::Upload('capa', @$this->_data['table']['capa'], 'imagem');

    $noticia_cat = new NoticiaCatModel();
    $this->_data['table']['id_noticia_cat'] = $noticia_cat->getDropdown('Selecione uma Categoria', @$this->_data['table']['id_noticia_cat']);

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
      );

      if ( !$id ) {
        $id = $this->_model->inserir($info);
      } else {
        $this->_model->atualizar($id, $info);
      }

      $this->setRegistroSalvoSession();

      $redirect = '/admin/noticia/cadastro/' . $id . '/';
      if ( Post::text('redirect') == 'lista' ) {
        $redirect = '/admin/noticia/lista/';
      }

      JsonViewHelper::redirect($redirect);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
