<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
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

  public function validate_insert ( $input )
  {
    $this->setNewId();
    $this->_table->id_poll = $input['id_poll'];

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('option', 'Alternativa');
  }

  public function validate_update ( $input )
  {
    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('option', 'Alternativa');
  }

  public function insert ()
  {
    $this->dao_insert(false);
  }

  public function update ()
  {
    $this->dao_update(false);
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
