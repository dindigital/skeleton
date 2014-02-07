<?php

namespace src\app\admin\models;

use src\app\admin\validators\PollOptionValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;

/**
 *
 * @package app.models
 */
class PollOptionModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('poll_option');
  }

  public function validate_insert ( $info )
  {
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setOption($info['option']);
    $validator->setIdPoll($info['id_poll']);
  }

  public function validate_update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setOption($info['option']);
  }

  public function insert ()
  {
    $this->_dao->insert($this->_table);
    $this->log('C', $this->_table->option, $this->_table);
  }

  public function update ()
  {
    $tableHistory = $this->getById();

    $this->_dao->update($this->_table, array('id_poll_option = ?' => $this->getId()));
    $this->log('U', $this->_table->option, $this->_table, $tableHistory);
  }

  public function getByIdPoll ( $id_poll )
  {
    $select = new Select('poll_option');
    $select->addField('id_poll_option');
    $select->addField('option');

    $select->where(array(
        'id_poll = ?' => $id_poll
    ));

    $result = $this->_dao->select($select);

    return $result;
  }

}
