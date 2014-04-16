<?php

namespace Admin\Models\Essential;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Admin\Helpers\MoveFiles;
use Din\InputValidator\InputValidator;
use Din\TableFilter\TableFilter;
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

    $v = new InputValidator($input);
    $has_file = $v->upload()->validate('file', 'Foto');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->labelCredit()->filter('file');
    $f->newId()->filter($this->_id_photo_item);
    $f->string()->filter($this->_id_photo);
    $f->string()->filter('sequence');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/{$this->_photo}/{$input[$this->_id_photo]}/{$this->_photo_item}/{$this->getId()}/file"
            , $has_file
            , $mf)->filter('file');
    //
    $mf->move();

    $this->_dao->insert($this->_table);

  }

  public function update ( $input )
  {
    //
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('sequence');
    $f->string()->filter('label');
    $f->string()->filter('credit');
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
