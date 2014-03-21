<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\validators\UploadValidator;
use src\app\admin\filters\TableFilter;
use src\app\admin\filters\FileFilter;
use Din\Exception\JsonException;
use Din\File\Folder;

/**
 *
 * @package app.models
 */
class GaleryModel extends BaseModelAdm
{

  protected $_photo;
  protected $_photo_item;
  protected $_id_photo;
  protected $_id_photo_item;

  public function __construct ( $options )
  {
    parent::__construct();
    $this->setTable($options['photo_item']);

    $this->_photo = $options['photo'];
    $this->_photo_item = $options['photo_item'];
    $this->_id_photo = $options['id_photo'];
    $this->_id_photo_item = $options['id_photo_item'];
  }

  public function getList ( $arrCriteria = array() )
  {

    $select = new Select($this->_photo_item);
    $select->addAllFields();
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function getIdName ()
  {
    return $this->_id_photo_item;
  }

  public function insert ( $input )
  {
    $input['file'] = array($input['file']);
    //
    $upl_validator = new UploadValidator($input);
    $has_file = $upl_validator->validateFile('file');
    //
    JsonException::throwException();
    //
    $file_filter = new FileFilter($this->_table, $input);
    $file_filter->setLabelCredit('file');
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId($this->_id_photo_item);
    $filter->setString($this->_id_photo);
    $filter->setString('sequence');
    //

    $mf = new MoveFiles;
    if ( $has_file ) {
      $filter->setUploaded('file', "/system/uploads/{$this->_photo}/{$input[$this->_id_photo]}/{$this->_photo_item}/{$this->getId()}/file");
      $mf->addFile($input['file'][0]['tmp_name'], $this->_table->file);
    }
    $mf->move();

    $this->_dao->insert($this->_table);
  }

  public function update ( $input )
  {
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setString('sequence');
    $filter->setString('label');
    $filter->setString('credit');
    //
    $this->_dao->update($this->_table, array(
        $this->_id_photo_item . ' = ?' => $this->getId()
    ));
  }

  public function batch_delete ( $id_photo, $gallery_sequence )
  {
    $arr_criteria = array(
        "{$this->_id_photo} = ?" => $id_photo,
        "{$this->_id_photo_item} NOT IN (?)" => $gallery_sequence,
    );

    $select = new Select($this->_photo_item);
    $select->addField($this->_id_photo_item);
    $select->where($arr_criteria);
    $result = $this->_dao->select($select);

    foreach ( $result as $row ) {
      Folder::delete("public/system/uploads/{$this->_photo}/{$id_photo}/{$this->_photo_item}/{$row[$this->_id_photo_item]}");
    }

    $this->_dao->delete($this->_photo_item, $arr_criteria);
  }

  public function batch_delete_all ( $id_photo )
  {
    $arr_criteria = array(
        "{$this->_id_photo} = ?" => $id_photo,
    );

    Folder::delete("public/system/uploads/{$this->_photo}/{$id_photo}/{$this->_photo_item}/");

    $this->_dao->delete($this->_photo_item, $arr_criteria);
  }

  public function saveGalery ( $upload, $id_photo, $gallery_sequence = null, $label = null, $credit = null )
  {
    //_# RESOLVE A ORDEM
    if ( $gallery_sequence === '' ) {
      $this->batch_delete_all($id_photo);
    } elseif ( $gallery_sequence ) {
      $gallery_sequence = explode(',', $gallery_sequence);
      $this->batch_delete($id_photo, $gallery_sequence);

      foreach ( $gallery_sequence as $i => $id_item ) {
        $this->setId($id_item);
        $this->update(array(
            'label' => $label[$i],
            'credit' => $credit[$i],
            'sequence' => ($i + 1),
        ));
      }
    }

    $sequence = count($gallery_sequence) + 1;

    //_# SALVA NOVAS FOTOS
    foreach ( $upload as $file ) {
      $this->insert(array(
          $this->_id_photo => $id_photo,
          'sequence' => $sequence,
          'file' => $file
      ));
    }
  }

}
