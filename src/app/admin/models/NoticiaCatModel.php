<?php

namespace src\app\admin\models;

use src\app\admin\validators\NoticiaCatValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use \Exception;
use Din\Form\Dropdown\Dropdown;
use src\app\admin\models\HierarquiaModel;

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

    $select = new Select('noticia_cat');
    $select->addField('id_noticia_cat');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('inc_data');
    $select->where($arrCriteria);
    $select->order_by('titulo');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new NoticiaCatValidator();
    $id = $validator->setIdNoticiaCat()->getTable()->id_noticia_cat;
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setIncData();
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

  public function excluir ( $id )
  {
    $tableHistory = $this->getById($id);
    HierarquiaModel::excluirFilhos('noticia_cat', $id, $this->_dao);
    $validator = new NoticiaCatValidator();
    $validator->setDelData();
    $validator->setDel('1');
    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
    $this->log('T', $tableHistory['titulo'], 'noticia_cat', $tableHistory);
  }

  public function restaurar ( $id )
  {
    $tableHistory = $this->getById($id);
    $validator = new NoticiaCatValidator();
    $validator->setDel('0');
    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
    $this->log('R', $tableHistory['titulo'], 'noticia_cat', $tableHistory);
  }

  public function excluir_permanente ( $id )
  {
    $tableHistory = $this->getById($id);
    $lixeira = new LixeiraModel();
    $lixeira->excluirFilhos('noticia_cat', $id, $this->_dao);
    $this->_dao->delete('noticia_cat', array('id_noticia_cat = ?' => $id));
    $this->log('D', $tableHistory['titulo'], 'noticia_cat', $tableHistory);
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $tableHistory = $this->getById($id);
    $validator = new NoticiaCatValidator();
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
    $this->log('U', $tableHistory['titulo'], $validator->getTable(), $tableHistory);
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
