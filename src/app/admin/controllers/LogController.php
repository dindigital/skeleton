<?php

namespace src\app\admin\controllers;

use src\app\admin\BaseControllerAdm;
use src\app\admin\models\LogModel;
use src\app\admin\objects\PaginatorPainel;
use src\app\admin\objects\Arrays;
use src\app\admin\objects\Dropdown;

/**
 *
 * @package app.controllers
 */
class LogController extends BaseControllerAdm
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      parent::__construct($app_name, $Compressor);

      $this->_model = new LogModel();
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public static function inserir_manual ( $responsavel, $secao, $nome_legivel, $crud, $descricao )
  {
    $log = new LogModel();
    $log->inserir($responsavel, $crud, $secao, $nome_legivel, $descricao, null);
  }

  public static function inserir ( $controller, $id, $content = array(), $crud = null )
  {
    $usuario = $controller->user_table->nome;

    if ( !$crud )
      $crud = $id ? 'U' : 'C';

    switch ($crud) {
      case 'C':
        $content['insert'] = $content['insert']->getFields();

        $secao = $controller->_model->_table->getName(true);
        $nome_legivel = $controller->_model->getMe();
        $descricao = $content['insert'][$controller->_model->_table->getTitle()];
        break;
      case 'U':
        $content['after'] = $content['after']->getFields();
        $descricao = @$content['after'][$controller->_model->_table->getTitle()];

        $content['before'] = $content['before']->getFields();

        // remove o que não for diferente, ou seja, manter apenas os campos onde
        // houveram alterações
        foreach ( $content['after'] as $k => $v ) {
          if ( $v == $content['before'][$k] ) {
            unset($content['before'][$k]);
            unset($content['after'][$k]);
          }
        }

        $secao = $controller->_model->_table->getName(true);
        $nome_legivel = $controller->_model->getMe();

        break;
      default:
        throw new \Exception('Ação de log desconhecida');
    }

    $log = new LogModel();
    $log->inserir($usuario, $crud, $secao, $nome_legivel, $descricao, $content);
  }

  public function get_lista ()
  {
    try {

      $arrCriteria = array();

      $this->busca->crud = '';

      if ( isset($_GET['crud']) && $_GET['crud'] != '' && $_GET['crud'] != '0' ) {
        $arrCriteria['crud'] = $_GET['crud'];
        $this->busca->crud = $_GET['crud'];
      }

      $this->busca->secao = '';

      if ( isset($_GET['secao']) && $_GET['secao'] != '' && $_GET['secao'] != '0' ) {
        $arrCriteria['secao'] = $_GET['secao'];
        $this->busca->secao = $_GET['secao'];
      }

      $this->busca->responsavel = '';

      if ( isset($_GET['responsavel']) && $_GET['responsavel'] != '' && $_GET['responsavel'] != '0' ) {
        $arrCriteria['responsavel'] = $_GET['responsavel'];
        $this->busca->responsavel = $_GET['responsavel'];
      }

      $this->busca->crud = Dropdown::get('crud', Arrays::arrayLogCrud(), $this->busca->crud, 'Filtro por Ação');
      $this->busca->secao = Dropdown::get('secao', Arrays::arrayLogSecao(), $this->busca->secao, 'Filtro por Seção');
      $this->busca->responsavel = $this->_model->getDropdownResponsavel('Filto por Responsável', $this->busca->responsavel);

      $this->paginator = new PaginatorPainel(20, 7, @$_GET['pag']);

      $this->list = $this->_model->listar($arrCriteria, $this->paginator);
      $this->action = $this->uri->lista;

      $this->alljax_view($this->uri->view_lista);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_cadastro ( $id, $salvo = false )
  {

    try {

      $this->action = $this->uri->cadastro;

      $this->action .= $id . '/';
      $this->table = $this->_model->getById($id);

      $this->registro_salvo($salvo);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

