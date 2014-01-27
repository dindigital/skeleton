<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\validators\PhotoItemValidator as validator;
use Din\DataAccessLayer\Select;

/**
 *
 * @package app.models
 */
class PhotoItemModel extends BaseModelAdm
{

  public function getList ( $arrCriteria = array() )
  {

    $select = new Select('photo_item');
    $select->addField('*');
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $validator = new validator($this->_dao);
    $id = $validator->setId($this);
    $validator->setIdPhoto($info['id_photo']);
    $validator->setSequence2(null, $info['id_photo']);
    $validator->setGallery($info['file'], "photo/{$info['id_photo']}/file/{$id}/");
    $validator->throwException();

    $this->_dao->insert($validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator($this->_dao);
    $validator->setLabel($info['label']);
    $validator->setCredit($info['credit']);
    $validator->setSequence2($info['sequence']);

    $this->_dao->update($validator->getTable(), array('id_photo_item = ?' => $id));

    return $id;
  }

  public function remove ( $id_photo, $gallery_sequence )
  {
    $gallery_sequence = explode(',', $gallery_sequence);

    $arrCriteria = array(
        'id_photo = ?' => $id_photo,
        'id_photo_item NOT IN ?' => $gallery_sequence,
    );

    $select = new Select('photo_item');
    $select->addField('file');
    $select->where($arrCriteria);
    $result = $this->_dao->select($select);

    foreach ( $result as $row ) {
      @unlink(WEBROOT . '/public/' . $row['file']);
    }

    $this->_dao->delete('photo_item', $arrCriteria);
  }

  public function save ( $upload, $id, $gallery_sequence = null, $label = null, $credit = null )
  {
    $this->remove($id, $gallery_sequence);

    //_# RESOLVE A ORDEM
    if ( $gallery_sequence ) {
      foreach ( explode(',', $gallery_sequence) as $i => $id_photo_item ) {
        $this->update($id_photo_item, array(
            'label' => $label[$i],
            'credit' => $credit[$i],
            'sequence' => ($i + 1),
        ));
      }
    }

    //_# SALVA NOVAS FOTOS
    foreach ( $upload as $file ) {
      if ( count($file) == 2 ) {
        $label = pathinfo($file['name'], PATHINFO_FILENAME);
        $this->insert(array(
            'id_photo' => $id,
            'label' => $label,
            'file' => $file
        ));
      }
    }
  }

}
