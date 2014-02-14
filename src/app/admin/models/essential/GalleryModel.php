<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\GalleryValidator as validator;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\Entities;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class GalleryModel extends BaseModelAdm
{

  protected $_table_item;
  protected $_entity;

  public function __construct ( $table, $table_item )
  {
    parent::__construct();
    $this->setTable($table_item);

    $entities = new Entities();
    $this->_entity = $entities->getEntity($table);
    $this->_table_item = $entities->getEntity($table_item);
  }

  public function getList ( $arrCriteria = array() )
  {

    $select = new Select($this->_table_item['tbl']);
    $select->addAllFields();
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->_table->{$this->_entity['id']} = $input[$this->_entity['id']];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setGallerySequence($this->_table_item['tbl'], $this->_entity['id'], null, $input[$this->_entity['id']]);

    $mf = new MoveFiles;
    $path = "{$this->_entity['tbl']}/{$input[$this->_entity['id']]}/file/{$this->getId()}"; // photo/1/file/1/
    $validator->setGallery('file', $path, $mf);
    $validator->throwException();

    $this->readTags($mf->getFiles());

    $mf->move();

    $this->_dao->insert($this->_table);
  }

  public function update ( $input )
  {
    $this->_table->label = $input['label'];
    $this->_table->credit = $input['credit'];

    $validator = new validator($this->_table, $this->_dao);
    $validator->setGallerySequence($this->_table_item['tbl'], $this->_entity['id'], $input['sequence']);

    $this->_dao->update($this->_table, array("{$this->_table_item['id']} = ?" => $this->getId()));
  }

  public function remove ( $id, $gallery_sequence )
  {

    $gallery_sequence = explode(',', $gallery_sequence);

    $arrCriteria = array(
        "{$this->_entity['id']} = ?" => $id,
        "{$this->_table_item['id']} NOT IN (?)" => $gallery_sequence,
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

  public function saveGalery ( $upload, $id, $gallery_sequence = null, $label = null, $credit = null )
  {
    $this->remove($id, $gallery_sequence);
    //_# RESOLVE A ORDEM
    if ( $gallery_sequence ) {
      foreach ( explode(',', $gallery_sequence) as $i => $id_item ) {
        $this->setId($id_item);
        $this->update(array(
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
            $this->_entity['id'] => $id,
            'label' => $label,
            'file' => $file
        ));
      }
    }
  }

  protected function readTags ( $file )
  {
    $file = $file[0]['origin'];

    if ( !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'tiff')) )
      return;

    $exif = exif_read_data($file);

    if ( isset($exif['ImageDescription']) ) {
      $this->_table->label = $exif['ImageDescription'];
    }

    if ( isset($exif['Artist']) ) {
      $this->_table->credit = $exif['Artist'];
    }
  }

}
