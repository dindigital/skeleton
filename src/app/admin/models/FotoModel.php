<?php

namespace src\app\admin\models;

use src\app\admin\validators\FotoValidator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;

/**
 *
 * @package app.models
 */
class FotoModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $row = parent::getById($id);

    $foto_item = new FotoItemModel();
    $row['galeria'] = $foto_item->listar(array('id_foto = ?' => $id));

    return $row;
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'del = ?' => '0',
        'titulo LIKE ?' => '%' . $arrFilters['titulo'] . '%'
    );

    $select = new Select('foto');
    $select->addField('id_foto');
    $select->addField('ativo');
    $select->addField('titulo');
    $select->addField('data');
    $select->where($arrCriteria);
    $select->order_by('titulo');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new FotoValidator();
    $id = $validator->setId($this);
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setIncData();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['titulo'], $validator->getTable());

    $foto_item = new FotoItemModel();
    $foto_item->saveFotos($info['galeria_uploader'], $id);

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new FotoValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_foto = ?' => $id));
    $this->log('U', $info['titulo'], $validator->getTable(), $tableHistory);

    $foto_item = new FotoItemModel();
    $foto_item->saveFotos($info['galeria_uploader'], $id, $info['ordem'], $info['legenda'], $info['credito']);

    return $id;
  }

}
