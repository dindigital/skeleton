<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;
use Din\DataAccessLayer\Select;

class TagValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('tag');
  }

  public function setTitle ( $title )
  {
    if ( !v::string()->notEmpty()->validate($title) )
      return JsonException::addException('Titulo é obrigatório');

    if ( !v::string()->length(1, 255)->validate($title) )
      return JsonException::addException('Titulo pode ter no máximo 255 caracteres.');

    $this->_table->title = $title;
  }

  public function setTag ( $dao, $row )
  {

    $arrCriteria = array(
        'id_tag = ?' => $row
    );

    $select = new Select('tag');
    $select->addField('id_tag');
    $select->where($arrCriteria);

    $result = $dao->select($select);

    if ( count($result) )
      return false;

    $this->_table->title = $row;

    return true;
  }

}
