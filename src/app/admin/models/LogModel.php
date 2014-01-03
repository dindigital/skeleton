<?php

namespace src\app\adm005\models;

use src\models\Log;
use lib\Paginator\Paginator;

/**
 *
 * @package app.models
 */
class LogModel extends Log
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Log');
  }

  public function setResponsavel ( $responsavel )
  {
    $this->_table->responsavel = $responsavel;
  }

  public function setCrud ( $crud )
  {
    if ( $crud == '' )
      throw new \Exception('Crud é obrigatório');

    $this->_table->crud = $crud;
  }

  public function setSecao ( $secao )
  {
    if ( $secao == '' )
      throw new \Exception('Secao é obrigatório');

    $this->_table->secao = $secao;
  }

  public function setNomeLegivel ( $nome_legivel )
  {
    if ( $nome_legivel == '' )
      throw new \Exception('Nome legível é obrigatório');

    $this->_table->nome_legivel = $nome_legivel;
  }

  public function setDescricao ( $descricao )
  {
    $this->_table->descricao = $descricao;
  }

  public function setData ()
  {
    $this->_table->data = date('Y-m-d H:i:s');
  }

  public function saveContent ( $content, $id )
  {
    $mongo = $this->getMongo();
    if ( $mongo ) {
      $mongo->admin_log->insert(array(
          'id_admin_log' => $id,
          'content' => $content
      ));
    }
  }

  public function getById ( $id )
  {
    $SQL = '
    SELECT
      *
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $id);

    if ( !count($result) )
      throw new \Exception('AdminLog não encontrado.');

    $mongo = $this->getMongo();
    if ( $mongo ) {
      $result_mongo = $mongo->admin_log->findOne(array(
          'id_admin_log' => $id
      ));

      $result[0]->content = $result_mongo['content'];
    }

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $SQL = '
    SELECT
      id_log, data, crud, secao, responsavel, nome_legivel, descricao
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ORDER BY
      data DESC
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $responsavel, $crud, $secao, $nome_legivel, $descricao, $content )
  {
    $this->_table->clear();

    $this->setResponsavel($responsavel);
    $this->setCrud($crud);
    $this->setSecao($secao);
    $this->setNomeLegivel($nome_legivel);
    $this->setDescricao($descricao);
    $this->setData();

    $id = $this->_dao->insert($this->_table);

    $this->saveContent($content, $id);

    return $id;
  }

  public function getDropdownResponsavel ( $firstOption = '', $selected = null )
  {
    $d = new \lib\Form\Dropdown\Dropdown('responsavel');

    $SQL = "SELECT responsavel, responsavel FROM log {\$strWhere} GROUP BY responsavel ORDER BY responsavel";
    $arrayObj = $this->_dao->getByCriteria($this->_table, $SQL, array());
    $d->setOptionsObj($arrayObj, 'responsavel', 'responsavel');
    $d->setClass('uniform');
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

}

