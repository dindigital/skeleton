<?php

namespace src\app\admin\controllers;

use src\app\admin\models\UsuarioModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\PermissaoModel;
use src\app\admin\viewhelpers\UsuarioViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class UsuarioController extends BaseControllerAdm
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
        'nome' => Get::text('nome'),
        'email' => Get::text('email'),
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->listar($arrFilters, $paginator));
    $this->_data['busca'] = vh::formatFilters($arrFilters);

    $this->setErrorSessionData();

    $this->setListTemplate('usuario_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    $exclude_previous = array(
        'avatar', 'avatar2', 'avatar3'
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($exclude_previous);

    $permissao = new PermissaoModel();
    $permissao_listbox = $permissao->getListbox(@$this->_data['table']['permissao']);

    $this->_data['table'] = vh::formatRow($row, $permissao_listbox);

    $this->setCadastroTemplate('usuario_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'nome' => Post::text('nome'),
          'email' => Post::text('email'),
          'senha' => Post::text('senha'),
          'avatar' => Post::upload('avatar'),
          'avatar2' => Post::upload('avatar2'),
          'avatar3' => Post::upload('avatar3'),
          'permissao' => Post::aray('permissao'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
