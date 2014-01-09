<?php

namespace src\app\admin\models;

use src\app\admin\validators\NoticiaCatValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use \Exception;

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

    $this->_dao->insert($validator->getTable());

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new NoticiaCatValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->throwException();

    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));

    return $id;
  }

  public function excluir ( $id )
  {
    $validator = new NoticiaCatValidator();
    $validator->setDelData();
    $validator->setDel('1');
    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
  }

  public function restaurar ( $id )
  {
    $validator = new NoticiaCatValidator();
    $validator->setDel('0');
    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
  }

  public function excluir_permanente ( $id )
  {
    $this->_dao->delete('noticia_cat', array('id_noticia_cat = ?' => $id));
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $validator = new NoticiaCatValidator();
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array('id_noticia_cat = ?' => $id));
  }

}
