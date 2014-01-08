<?php

namespace src\app\admin\controllers;

use src\app\admin\models\FotoModel;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use src\app\admin\helpers\Form;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use \Exception;
use src\app\admin\helpers\Galeria;
use Din\Filters\Date\DateFormat;

/**
 *
 * @package app.controllers
 */
class FotoController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new FotoModel();
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

    $this->setListTemplate('foto_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    if ( $id ) {
      $this->_data['table'] = $this->_model->getById($id);
      $this->_data['table']['data'] = DateFormat::filter_date($this->_data['table']['data']);
    } else {
      $this->_data['table'] = array();
    }

    $this->_data['table']['galeria_uploader'] = Form::Upload('galeria_uploader', @$this->_data['table']['galeria'], 'imagem', true, false);
    $this->_data['table']['galeria'] = Galeria::get(@$this->_data['table']['galeria'], 'galeria');

    $this->setCadastroTemplate('foto_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'titulo' => Post::text('titulo'),
          'data' => Post::text('data'),
          'galeria_uploader' => Post::upload('galeria_uploader'),
      );

      if ( !$id ) {
        $id = $this->_model->inserir($info);
      } else {
        $this->_model->atualizar($id, array_merge($info, array(
            'ordem' => Post::text('galeria_ordem'),
            'legenda' => Post::aray('legenda'),
            'credito' => Post::aray('credito'),
        )));
      }

      $this->setRegistroSalvoSession();

      $redirect = '/admin/foto/cadastro/' . $id . '/';
      if ( Post::text('redirect') == 'lista' ) {
        $redirect = '/admin/foto/lista/';
      }

      JsonViewHelper::redirect($redirect);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
