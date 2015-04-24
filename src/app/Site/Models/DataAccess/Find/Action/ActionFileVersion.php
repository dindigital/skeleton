<?php

namespace Site\Models\DataAccess\Find\Action;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ActionFileVersionCollection;

class ActionFileVersion extends AbstractFind
{

  public function getByIdActionFile ( $id_action_file )
  {
    $select = new Select2('action_file_version');
    $select->addField('id_action_file');
    $select->addField('title');
    $select->addField('file');
    $select->addField('has_issuu');

    $select->left_join('issuu', 'id_issuu', 'id_issuu');
    $select->addField('link', 'issuu_link', 'issuu');
    $select->addField('document_id', 'issuu_document_id', 'issuu');
    $select->addField('name', 'issuu_name', 'issuu');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'id_action_file = ?' => $id_action_file,
    )));

    $select->order_by('title');

    $collection = $this->_dao->select_iterator($select, new Entity\ActionFileVersion, new ActionFileVersionCollection);

    return $collection;

  }

  public function getIssuuByIdAction ( $id_action )
  {
    $select = new Select2('action_file_version');
    $select->addField('id_action_file');
    $select->addField('title');
    $select->addField('file');
    $select->addField('has_issuu');

    $select->inner_join('issuu', 'id_issuu', 'id_issuu');
    $select->addField('link', 'issuu_link', 'issuu');
    $select->addField('document_id', 'issuu_document_id', 'issuu');
    $select->addField('name', 'issuu_name', 'issuu');

    $select->inner_join('action_file', 'id_action_file', 'id_action_file');

    $select->where(new Criteria(array(
        'action_file_version.is_active = ?' => 1,
        'action_file_version.is_del = ?' => 0,
        'action_file.id_action = ?' => $id_action,
    )));

    $select->order_by('title');

    $collection = $this->_dao->select_iterator($select, new Entity\ActionFileVersion, new ActionFileVersionCollection);

    return $collection;

  }

  public function getIssuuByIdActionFile ( $id_action_file )
  {
    $select = new Select2('action_file_version');
    $select->addField('id_action_file');
    $select->addField('title');
    $select->addField('file');
    $select->addField('has_issuu');

    $select->inner_join('issuu', 'id_issuu', 'id_issuu');
    $select->addField('link', 'issuu_link', 'issuu');
    $select->addField('document_id', 'issuu_document_id', 'issuu');
    $select->addField('name', 'issuu_name', 'issuu');

    $select->inner_join('action_file', 'id_action_file', 'id_action_file');

    $select->where(new Criteria(array(
        'action_file_version.is_active = ?' => 1,
        'action_file_version.is_del = ?' => 0,
        'action_file.id_action_file = ?' => $id_action_file,
    )));

    $select->order_by('title');

    $collection = $this->_dao->select_iterator($select, new Entity\ActionFileVersion, new ActionFileVersionCollection);

    return $collection;

  }

}
