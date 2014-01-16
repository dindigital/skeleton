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

    if ( $arrFilters['acao'] != '0' ) {
      $arrCriteria['a.acao = ?'] = $arrFilters['acao'];
    }

    if ( $arrFilters['secao'] != '0' ) {
      $arrCriteria['a.tabela = ?'] = $arrFilters['secao'];
    }

    $select = new Select('log');
    $select->addField('id_log');
    $select->addField('administrador');
    $select->addField('tabela');
    $select->addField('inc_data');
    $select->addField('acao');
    $select->addField('descricao');
    $select->where($arrCriteria);
    $select->order_by('a.inc_data DESC');

    $this->setPaginationSelect($select, $paginator);
    $result = $this->_dao->select($select);

    return $result;
  }

  public function getDropdownAction ( $firstOption = '', $selected = null )
  {
    $arrOptions = Arrays::logAcao();
    $d = new Dropdown('acao');
    $d->setOptionsArray($arrOptions);
    $d->setClass('form-control');
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

  public function getDropdownSecao ( $firstOption = '', $selected = null )
  {
    $arrOptions = array();
    foreach ( Entities::$entities as $row ) {
      $arrOptions[$row['tbl']] = $row['name'];
    }

    $d = new Dropdown('secao');
    $d->setOptionsArray($arrOptions);
    $d->setClass('form-control');
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

}
