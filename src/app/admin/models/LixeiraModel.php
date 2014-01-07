<?php

namespace src\app\adm005\models;

use lib\Mvc\Model\BaseModel;
use lib\Paginator\Paginator;

/**
 *
 * @package app.models
 */
class LixeiraModel extends BaseModel
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Lixeira');

    $this->_table = new \src\tables\LixeiraTable;
    $this->_models = array(
        'Marca',
        'Relato',
        'Caso',
        'Produto',
        'Servico',
    );
  }

  protected function setTbl ( $tbl )
  {
    $tbl = "\\src\\tables\\{$tbl}";
    $this->_table = new $tbl();
  }

  protected function setModel ( $model )
  {
    $model .= 'Model';
    $model = "\\src\\app\\adm005\\models\\{$model}";
    $this->_model = new $model();
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $arrSQL = array();
    $arrIN = array();

    if ( isset($arrParams['secao']) ) {
      $key = array_search($arrParams['secao'], $this->_models);
      $this->_models = array($this->_models[$key]);
    }

    foreach ( $this->_models as $model ) {
      $this->setModel($model);

      $table_name = $this->_model->_table->getName();
      $id_field = $this->_model->_table->getPk(true);
      $title_field = $this->_model->_table->getTitle();
      $sessao = $this->_model->getMe();

      $arrSQL[] = '
      SELECT
        ' . $id_field . ' id,
        ' . $title_field . ' titulo,
        del_data,
        "' . $sessao . '" secao,
        "' . $model . '" tbl

      FROM
        ' . $table_name . '
      WHERE
        del = 1 AND ' . $title_field . ' LIKE ?
      ';

      $arrIN[] = '%' . @$arrParams['titulo'] . '%';
    }

    $SQL = implode('UNION', $arrSQL);
    $SQL .= '
    ORDER BY
      del_data DESC
    ';

    //_# PEGANDO O TOTAL DE REGISTROS
    $SQL2 = "SELECT COUNT(*) total FROM ({$SQL}) fake";
    $result_total = $this->_dao->_driver->select($SQL2, $arrIN, $this->_table);
    $total = $result_total[0]->total;
    //_#

    $SQL = $Paginator->getSQL($SQL, $arrIN, null, $total);
    $result = $this->_dao->_driver->select($SQL, $arrIN, $this->_table);

    return $result;
  }

  public function inserir ( $itens )
  {
    foreach ( $itens as $item ) {
      $tbl = $item['tbl'];
      $id = $item['id'];

      $this->setModel($tbl);

      /* $valores_atuais = $this->_model->getById($id);
        if ( $id_entidade && $valores_atuais->id_entidade && $valores_atuais->id_entidade != $id_entidade )
        throw new \Exception('Permissão negada'); */

      $this->_model->_table->del = 1;
      $this->_model->_table->del_data = date('Y-m-d H:i:s');
      $this->_model->changeOrdem($id, 0, array(), true);

      $this->_dao->update($this->_model->_table, $id);
    }
  }

  public function restaurar ( $itens )
  {
    foreach ( $itens as $item ) {
      $tbl = $item['tbl'];
      $id = $item['id'];

      $this->setModel($tbl);
      $result = $this->_model->getById($id);
      $this->_model->_table = $result;

      $this->_model->setOrdem();
      $this->_model->_table->del = 0;
      $this->_model->_table->del_data = null;

      $this->_dao->update($this->_model->_table, $id);
    }
  }

  public function excluir ( $itens )
  {
    foreach ( $itens as $item ) {
      $tbl = $item['tbl'];
      $id = $item['id'];

      $this->setModel($tbl);
      $this->_model->excluir($id);
    }
  }

  public function getDropdown ( $firstOption = '', $selected = null )
  {
    $arrOptions = array();

    foreach ( $this->_models as $model ) {
      $this->setModel($model);
      $arrOptions[$model] = $this->_model->getMe();
    }

    $d = new \lib\Form\Dropdown\Dropdown('secao');
    $d->setOptionsArray($arrOptions);
    $d->setClass('uniform');
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

}

