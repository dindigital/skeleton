<?php

namespace src\app\admin\models;

use src\app\admin\validators\PhotoValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Paginator\Paginator;

/**
 *
 * @package app.models
 */
class PhotoModel extends BaseModelAdm
{

  public function getById ( $id )
  {
    $row = parent::getById($id);

    $photo_item = new PhotoItemModel();
    $row['gallery'] = $photo_item->getList(array('id_photo = ?' => $id));

    return $row;
  }

  public function getList ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . @$arrFilters['title'] . '%'
    );

    $select = new Select('photo');
    $select->addField('id_photo');
    $select->addField('active');
    $select->addField('title');
    $select->addField('date');
    $select->where($arrCriteria);
    $select->order_by('date DESC');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $validator = new validator();
    $id = $validator->setId($this);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());

    $photo_item = new PhotoItemModel();
    $photo_item->save($info['gallery_uploader'], $id);

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_photo = ?' => $id));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    $photo_item = new PhotoItemModel();
    $photo_item->save($info['gallery_uploader'], $id, $info['sequence'], $info['label'], $info['credit']);

    return $id;
  }

}

