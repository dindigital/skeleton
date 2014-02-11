<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;

/**
 *
 * @package app.models
 */
class ActiveModel extends BaseModelAdm
{

  protected $_model;

  public function setModelByName ( $name )
  {
    $current = Entities::getEntityByName($name);
    $this->_model = new $current['model'];
  }

  public function toggleActive ( $id, $active )
  {
    $current = Entities::getThis($this->_model);

    $tableHistory = $this->_model->getById($id);
    $this->_model->setIntval('active', $active);
    $this->_dao->update($this->_model->_table, array($current['id'] . ' = ?' => $id));
    $this->_model->log('U', $tableHistory[$current['title']], $this->_model->_table, $tableHistory);
  }

}
