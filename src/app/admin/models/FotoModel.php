<?php

namespace src\app\admin\models;

use src\app\admin\validators\FotoValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;
use \Exception;
use Din\Exception\JsonException;
use Din\File\Folder;

/**
 *
 * @package app.models
 */
class FotoModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_foto = ?' => $id
    );

    $select = new Select('foto');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Galeria não encontrada.');

    $row = $result[0];

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

  public function setUpload ( $upload, $id, $galeria_ordem = null, $legenda = null, $credito = null )
  {
    $foto_item = new FotoItemModel();
    $foto_item->deletar_removidas($id, $galeria_ordem);

    //_# RESOLVE A ORDEM
    if ( $galeria_ordem ) {
      foreach ( explode(',', $galeria_ordem) as $i => $id_foto_item ) {
        $foto_item->atualizar($id_foto_item, array(
            //'id_foto' => $id_foto_item,
            'legenda' => $legenda[$i],
            'credito' => $credito[$i],
            'ordem' => ($i + 1),
        ));
      }
    }

    //_# SALVA NOVAS FOTOS
    foreach ( $upload as $arquivo ) {
      if ( count($arquivo) == 2 ) {
        $legenda = pathinfo($arquivo['name'], PATHINFO_FILENAME);
        $foto_item->inserir(array(
            'id_foto' => $id,
            'legenda' => $legenda,
            'arquivo' => $arquivo
        ));
      }
    }
  }

  public function inserir ( $info )
  {
    $validator = new FotoValidator();
    $id = $validator->setIdFoto()->getTable()->id_foto;
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->setIncData();
    $validator->throwException();

    try {
      $this->_dao->insert($validator->getTable());
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    $this->setUpload($info['galeria_uploader'], $id);

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new FotoValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->throwException();

    try {
      $this->_dao->update($validator->getTable(), array('id_foto = ?' => $id));
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    $this->setUpload($info['galeria_uploader'], $id, $info['ordem'], $info['legenda'], $info['credito']);

    return $id;
  }

  public function excluir ( $id )
  {
    $validator = new FotoValidator();
    $validator->setDelData();
    $validator->setDel('1');
    $this->_dao->update($validator->getTable(), array('id_foto = ?' => $id));
  }

  public function restaurar ( $id )
  {
    $validator = new FotoValidator();
    $validator->setDel('0');
    $this->_dao->update($validator->getTable(), array('id_foto = ?' => $id));
  }

  public function excluir_permanente ( $id )
  {
    Folder::delete("public/system/uploads/foto/{$id}");
    $this->_dao->delete('foto', array('id_foto = ?' => $id));
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $validator = new FotoValidator();
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array('id_foto = ?' => $id));
  }

}
