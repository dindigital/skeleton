<?php

namespace src\app\admin\models;

use src\app\admin\validators\FotoValidator;
use src\app\admin\models\BaseModelAdm;
use Din\DataAccessLayer\Select;

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
    $row->galeria = $foto_item->listar(array('id_foto' => $id));

    return $row;
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null, $relacao = '' )
  {
    $arrParams['del'] = '0';

    $SQL = '
    SELECT
      a.id_foto, a.ativo, a.titulo
    FROM
      ' . $this->_table->getName() . ' a
    ' . $this->innerJoinRelacao($relacao) . '
    {$strWhere}
    ORDER BY
      a.titulo
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function setUpload ( $upload, $id, $galeria_ordem = null, $legenda = null, $credito = null )
  {
    $foto_item = new FotoItemModel();
    $foto_item->deletar_removidas($id, $galeria_ordem);

    //_# RESOLVE A ORDEM
    if ( $galeria_ordem ) {
      foreach ( explode(',', $galeria_ordem) as $i => $id_foto_item ) {
        $foto_item->atualizar(array(
            'id_foto' => $id_foto_item,
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
    $validator->setTitulo($info['titulo']);
    $validator->setData($info['data']);
    $validator->throwException();

    $this->_dao->insert($validator->getTable());

    $this->setUpload($info['galeria_uploader'], $id);

    return $id;
  }

}
