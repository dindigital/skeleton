<?php

namespace src\app\admin\models;

use src\app\admin\models\BaseModelAdm;
use src\app\admin\validators\FotoItemValidator;
use Din\DataAccessLayer\Select;

/**
 *
 * @package app.models
 */
class FotoItemModel extends BaseModelAdm
{

  public function listar ( $arrCriteria = array() )
  {

    $select = new Select('foto_item');
    $select->addField('*');
    $select->where($arrCriteria);
    $select->order_by('ordem');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new FotoItemValidator($this->_dao);
    $id = $validator->setIdFotoItem()->getTable()->id_foto_item;
    $validator->setIdFoto($info['id_foto']);
    $validator->setOrdem2(null, $info['id_foto']);
    $validator->setGaleria($info['arquivo'], "foto/{$info['id_foto']}/arquivo/{$id}/");
    $validator->throwException();

    $this->_dao->insert($validator->getTable());

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new FotoItemValidator($this->_dao);
    $validator->setLegenda($info['legenda']);
    $validator->setCredito($info['credito']);
    $validator->setOrdem2($info['ordem']);

    $this->_dao->update($validator->getTable(), array('id_foto_item = ?' => $id));

    return $id;
  }

  public function deletar_removidas ( $id_foto, $galeria_ordem )
  {
    $galeria_ordem = explode(',', $galeria_ordem);

    $arrCriteria = array(
        'id_foto = ?' => $id_foto,
        'id_foto_item NOT IN ?' => $galeria_ordem,
    );

    $select = new Select('foto_item');
    $select->addField('arquivo');
    $select->where($arrCriteria);
    $result = $this->_dao->select($select);

    foreach ( $result as $row ) {
      @unlink(WEBROOT . '/public/' . $row['arquivo']);
    }

    $this->_dao->delete('foto_item', $arrCriteria);
  }

  public function saveFotos ( $upload, $id, $galeria_ordem = null, $legenda = null, $credito = null )
  {
    $this->deletar_removidas($id, $galeria_ordem);

    //_# RESOLVE A ORDEM
    if ( $galeria_ordem ) {
      foreach ( explode(',', $galeria_ordem) as $i => $id_foto_item ) {
        $this->atualizar($id_foto_item, array(
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
        $this->inserir(array(
            'id_foto' => $id,
            'legenda' => $legenda,
            'arquivo' => $arquivo
        ));
      }
    }
  }

}
