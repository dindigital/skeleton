<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\GalleryValidator as validator;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\validators\UploadValidator;
use src\app\admin\helpers\TableFilter;
use Din\Exception\JsonException;

/**
 *
 * @package app.models
 */
class PhotoItemModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('photo_item');
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
    $upl_validator = new UploadValidator($input);
    $has_file = $upl_validator->validateFile('file');
    var_dump($has_file);
    exit;
    //
    JsonException::throwException();
    //
    $file_filter = new \src\app\admin\helpers\FileFilter($this->_table, $input);
    $file_filter->setLabelCredit('file');
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_photo_item');
    $filter->setString('id_photo');
    $filter->setString('sequence');
    //

    $mf = new MoveFiles;
    if ( $has_file ) {
      $filter->setUploaded('file', "/system/uploads/photo_item/{$this->getId()}/file");
      $mf->addFile($input['file'][0]['tmp_name'], $this->_table->file);
    }
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

  public function saveGalery ( $upload, $id )
  {
    //_# DESCOBRE A SEQUENCE DA ULTIMA FOTO JA EXISTENTE
    $select = new Select('photo_item');
    $select->where(array("id_photo = ?" => $id));

    $sequence = $this->_dao->select_count($select) + 1;
    //
    //_# SALVA NOVAS FOTOS
    foreach ( $upload as $file ) {
      $this->insert(array(
          'id_photo' => $id,
          'sequence' => $sequence,
          'file' => $file
      ));
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
