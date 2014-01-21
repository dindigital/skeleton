<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use Din\Paginator\Paginator;
use Din\Form\Dropdown\Dropdown;
use src\app\admin\helpers\Entities;
use src\app\admin\helpers\Arrays;

/**
 *
 * @package app.models
 */
class LogModel extends BaseModelAdm
{

  public function resultList ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'a.administrador LIKE ?' => '%' . $arrFilters['usuario'] . '%',
        'a.descricao LIKE ?' => '%' . $arrFilters['descricao'] . '%'
    );

    if ( $arrFilters['acao'] != '0' && $arrFilters['acao'] != '' ) {
      $arrCriteria['a.acao = ?'] = $arrFilters['acao'];
    }

    if ( $arrFilters['name'] != '0' && $arrFilters['name'] != '' ) {
      $arrCriteria['a.name = ?'] = $arrFilters['name'];
    }

    $select = new Select('log');
    $select->addField('id_log');
    $select->addField('administrador');
    $select->addField('name');
    $select->addField('inc_data');
    $select->addField('acao');
    $select->addField('descricao');
    $select->where($arrCriteria);
    $select->order_by('a.inc_data DESC');

    $this->setPaginationSelect($select, $paginator);
    $result = $this->_dao->select($select);

    return $result;
  }

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_log = ?' => $id
    );

    $select = new Select('log');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $result[0];

    return $row;
  }

  public function getDropdownName ()
  {
    $arrOptions = array();
    foreach ( Entities::$entities as $row ) {
      if ( isset($row['secao']) && isset($row['tbl']) && isset($row['name']) )
        $arrOptions[$row['name']] = $row['secao'];
    }

    return $arrOptions;
  }

}
