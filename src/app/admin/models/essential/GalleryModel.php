<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\GalleryValidator as validator;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\Entities;

/**
 *
 * @package app.models
 */
class GalleryModel extends BaseModelAdm
{

  private $_table;
  private $_table_item;

  public function __construct ( $table, $table_item )
  {
    parent::__construct();

    $entities = new Entities();
    $this->_table = $entities->getEntity($table);
    $this->_table_item = $entities->getEntity($table_item);
  }

  public function getList ( $arrCriteria = array() )
  {

    $select = new Select($this->_table_item['tbl']);
    $select->addField('*');
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {

    $validator = new validator($this->_dao, $this->_table_item['tbl']);
    $id = $validator->setId($this->_table_item['id']);
    $validator->setIdTbl($this->_table['id'], $info[$this->_table['id']]);
    $validator->setGallerySequence($this->_table_item['tbl'], $this->_table['id'], null, $info[$this->_table['id']]);
    $validator->setGallery($info['file'], "{$this->_table['tbl']}/{$info[$this->_table['id']]}/file/{$id}/");
    $validator->throwException();

    $this->_dao->insert($validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {

    $validator = new validator($this->_dao, $this->_table_item['tbl']);
    $validator->setLabel($info['label']);
    $validator->setCredit($info['credit']);
    $validator->setGallerySequence($this->_table_item['tbl'], $this->_table['id'], $info['sequence']);

    $this->_dao->update($validator->getTable(), array("{$this->_table_item['id']} = ?" => $id));

    return $id;
  }

  public function remove ( $id, $gallery_sequence )
  {

    $gallery_sequence = explode(',', $gallery_sequence);

    $arrCriteria = array(
        "{$this->_table['id']} = ?" => $id,
        "{$this->_table_item['id']} NOT IN ?" => $gallery_sequence,
    );

    $select = new Select($this->_table_item['tbl']);
    $select->addField('file');
    $select->where($arrCriteria);
    $result = $this->_dao->select($select);

    foreach ( $result as $row ) {
      @unlink(WEBROOT . '/public/' . $row['file']);
    }

    $this->_dao->delete($this->_table_item['tbl'], $arrCriteria);
  }

  public function save ( $upload, $id, $gallery_sequence = null, $label = null, $credit = null )
  {

    $this->remove($id, $gallery_sequence);
    //_# RESOLVE A ORDEM
    if ( $gallery_sequence ) {
      foreach ( explode(',', $gallery_sequence) as $i => $id_item ) {
        $this->update($id_item, array(
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
            $this->_table['id'] => $id,
            'label' => $label,
            'file' => $file
        ));
      }
    }
  }

}
