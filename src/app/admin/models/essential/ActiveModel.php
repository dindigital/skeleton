<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Table\Table;
use src\app\admin\filters\TableFilter;

/**
 *
 * @package app.models
 */
class ActiveModel extends BaseModelAdm
{

  protected $_model;

  public function setModelByTbl ( $tbl )
  {
    $entity = $this->_entities->getEntity($tbl);
    $model = $entity->getModel();
    $this->_model = new $model;
  }

  public function toggleActive ( $id, $active )
  {
    $table = new Table($this->_model->getTableName());
    $filter = new TableFilter($table, array(
        'active' => $active
    ));

    $filter->setIntval('active');

    $title = $this->_model->_entity->getTitle();

    $tableHistory = $this->_model->getById($id);
    $this->_dao->update($table, array($this->_model->getIdName() . ' = ?' => $id));
    $this->_model->log('U', $tableHistory[$title], $table, $tableHistory);
  }

}
