<?php

namespace src\app\admin\models;

use src\app\admin\validators\NoticiaCatValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use \Exception;
use Din\Form\Dropdown\Dropdown;
use src\app\admin\helpers\Ordem;

/**
 *
 * @package app.models
 */
class NoticiaCatModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_noticia_cat = ?' => $id
    );

    $select = new Select('noticia_cat');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Categoria não encontrada.');

    $row = $result[0];

    return $row;
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'del = ?' => '0',
        'titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );
    if ( isset($arrFilters['home']) && $arrFilters['home'] == '1' ) {
      $arrCriteria['home = ?'] = '1';
    } elseif ( isset($arrFilters['home']) && $arrFilters['home'] == '2' ) {
      $arrCriteria['home = ?'] = '0';
    }

    $select = new Select('noticia_cat');
    $select->addField('id_noticia_cat');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('inc_data');
    $select->addField('ordem');
    $select->where($arrCriteria);
    $select->order_by('ordem');

    $result = $this->_dao->select($select);
    $result = Ordem::setDropdown($this, $result, $arrCriteria);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new NoticiaCatValidator();
    $id = $validator->setIdNoticiaCat()->getTable()->id_noticia_cat;
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setHome($info['home']);
    $validator->setIncData();
    $validator->setArquivo('capa', $info['capa'], $id);

    Ordem::setOrdem($this, $validator);
    $validator->throwException();

    try {
      $this->_dao->insert($validator->getTable());
      $this->log('C', $info['titulo'], $validator->getTable());
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new NoticiaCatValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setHome($info['home']);
    $validator->setArquivo('capa', $info['capa'], $id);
    $validator->throwException();

    try {
      $tableHistory = $this->getById($id);
      $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
      $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    return $id;
  }

  public function getDropdown ( $firstOption = '', $selected = null )
  {
    $select = new Select('noticia_cat');
    $select->addField('id_noticia_cat');
    $select->addField('titulo');
    $select->where(array(
        'del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $d = new Dropdown('id_noticia_cat');
    $d->setOptionsResult($result, 'id_noticia_cat', 'titulo');
    $d->setClass('form-control');
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

}
