<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Admin\Custom_filter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class PollOptionModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('poll_option');

  }

  public function batch_validate ( $array_options )
  {
    foreach ( $array_options as $option ) {
      $input = array(
          'option' => $option
      );

      $v = new InputValidator($input);
      $v->string()->validate('option', 'Alternativa');
    }

  }

  public function batch_insert ( $id_poll, $array_options )
  {
    foreach ( $array_options as $sequence => $option ) {
      $input = array(
          'id_poll' => $id_poll,
          'option' => $option,
          'sequence' => $sequence + 1
      );

      $f = new TableFilter($this->_table, $input);
      $f->newId()->filter('id_poll_option');
      $f->string()->filter('id_poll');
      $f->string()->filter('option');
      $f->string()->filter('sequence');

      $this->_dao->insert($this->_table);
    }

  }

  public function batch_update ( $array_options )
  {
    foreach ( $array_options as $id_poll_option => $option ) {
      $input = array(
          'option' => $option
      );

      $f = new TableFilter($this->_table, $input);
      $f->string()->filter('option');

      $this->_dao->update($this->_table, array(
          'id_poll_option = ?' => $id_poll_option
      ));
    }

  }

  public function getByIdPoll ( $id_poll )
  {
    $select = new Select('poll_option');
    $select->addField('id_poll_option');
    $select->addField('option');

    $select->where(array(
        'id_poll = ?' => $id_poll
    ));

    $select->order_by('sequence');

    $result = $this->_dao->select($select);

    return $result;

  }

}
