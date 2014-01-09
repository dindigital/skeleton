<?php

namespace src\app\admin\models;

use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use \Exception;
use Din\Paginator\Paginator;
use Din\Form\Dropdown\Dropdown;

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
        'foto' => array(
            'tbl' => 'foto',
            'model' => 'Foto',
            'secao' => 'Fotos',
            'id' => 'id_foto',
            'title' => 'titulo'
        ),
        'noticia' => array(
            'tbl' => 'noticia',
            'model' => 'Noticia',
            'secao' => 'Notícias',
            'id' => 'id_noticia',
            'title' => 'titulo'
        ),
        'noticia_cat' => array(
            'tbl' => 'noticia_cat',
            'model' => 'NoticiaCat',
            'secao' => 'Categoria de Notícias',
            'id' => 'id_noticia_cat',
            'title' => 'titulo'
        ),
    );
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $itens = $this->_itens;

    if ( $arrFilters['secao'] != '0' ) {
      if ( isset($itens[$arrFilters['secao']]) ) {
        $itens = array($itens[$arrFilters['secao']]);
      }
    }

    $i = 0;
    foreach ( $itens as $item ) {

      $model = $item['model'];
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
      $select1->addSField('model', $model);
      $select1->where(array(
          'del = 1' => null,
          $title_field . ' LIKE ?' => '%' . $arrFilters['titulo'] . '%'
      ));

      if ( $i == 0 ) {
        $select = $select1;
      } else {
        $select->union($select1);
      }

      $i++;
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

    foreach ( $this->_itens as $model ) {
      $arrOptions[$model['tbl']] = $model['secao'];
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
