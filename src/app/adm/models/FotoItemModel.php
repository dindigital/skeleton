<?php

namespace src\app\adm\models;

use src\app\adm\models\BaseModelAdm;
use src\app\adm\validators\FotoItemValidator;

/**
 *
 * @package app.models
 */
class FotoItemModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $SQL = '
    SELECT
      *
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $id);

    if ( !count($result) )
      throw new \Exception('FotoItem não encontrado.');

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $SQL = '
    SELECT
      id_foto_item, legenda, credito, arquivo
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ORDER BY
      ordem
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $info )
  {
    $validator = new FotoItemValidator();
    $id = $validator->setIdFotoItem()->getTable()->id_foto_item;
    $validator->setIdFoto($info['id_foto']);
    $validator->setLegenda($info['legenda']);
    $validator->setOrdem2(null, $info['id_foto']);
    $validator->setGaleria('arquivo', $info['arquivo'], "fotos/{$info['id_foto']}/arquivo/{$id}-");

    $this->_dao->insert($validator->getTable());

    return $id;
  }

  public function deletar_removidas ( $id_foto, $galeria_ordem )
  {
    $arrCriteria = array(
        'id_foto = ?' => $id_foto,
            //'id_foto_item NOT IN ?' => $galeria_ordem,
    );

    $select = new \Din\DataAccessLayer\Select('foto_item');
    $select->addField('arquivo');
    $select->where($arrCriteria);
    $result = $this->_dao->select($select);
    foreach ( $result as $row ) {
      @unlink(WEBROOT . '/public/' . $row['arquivo']);
    }

    $this->_dao->delete('foto_item', $arrCriteria);
  }

}
