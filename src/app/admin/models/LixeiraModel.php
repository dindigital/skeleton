<?php

namespace src\app\admin\models;

use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use \Exception;
use Din\Paginator\Paginator;

/**
 *
 * @package app.models
 */
class LixeiraModel extends BaseModelAdm
{

  private $_itens;

  public function __construct ()
  {
    parent::__construct();

    $this->_itens = array(
        array(
            'tbl' => 'foto',
            'secao' => 'Foto',
            'id' => 'id_foto',
            'title' => 'titulo'
        ),
    );
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    foreach ( $this->_itens as $i => $item ) {

      $table_name = $item['tbl'];
      $id_field = $item['id'];
      $title_field = $item['title'];
      $secao = $item['secao'];

      $select1 = new Select($table_name);
      $select1->addField($id_field, 'id');
      $select1->addField($title_field);
      $select1->addField('del_data');
      $select1->addSField('secao', $secao);
      $select1->addSField('tbl', $table_name);
      $select1->where(array(
          'del = 1' => null,
          $title_field . ' LIKE ?' => '%' . $arrFilters['titulo'] . '%'
      ));

      if ( $i == 0 ) {
        $select = $select1;
      } else {
        $select->union($select1);
      }
    }

    $select->order_by('del_data DESC');

    $this->setPaginationSelect($select, $paginator);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function restaurar ( $itens )
  {
    foreach ( $itens as $item ) {
      list($tbl, $id) = explode('_', $item);

      $classname = '\src\app\admin\models\\' . ucfirst($tbl) . 'Model';

      $model = new $classname;
      $model->restaurar($id);
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
