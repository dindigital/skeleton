<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\FotoTable;
use Din\Exception\JsonException;

class FotoValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new FotoTable();
  }

  public function setIdFoto ()
  {
    $this->_table->id_foto = $this->_table->getNewId();

    return $this;
  }

  public function setTitulo ( $titulo )
  {
    if ( $titulo == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->titulo = $titulo;
  }

  public function setData ( $data )
  {
    $data = '2013-12-20';
//    if ( !\lib\Validation\Validate::data($data) )
//      return JsonException::addException('Data é obrigatório');
//
//    $data = \lib\Validation\StringTransform::sql_date($data);

    $this->_table->data = $data;
  }

}
