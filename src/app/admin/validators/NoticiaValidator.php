<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\NoticiaTable;
use Din\Exception\JsonException;
use Din\Filters\Date\DateToSql;
use Din\Filters\Date\DateFormat;

class NoticiaValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new NoticiaTable();
  }

  public function setIdNoticia ()
  {
    $this->_table->id_noticia = $this->_table->getNewId();

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
    if ( !DateFormat::validate($data) )
      return JsonException::addException('Data é obrigatório');

    $data = DateToSql::filter_date($data);

    $this->_table->data = $data;
  }

  public function setChamada ( $chamada )
  {
    $this->_table->chamada = $chamada;
  }

  public function setCorpo ( $corpo )
  {
    $this->_table->corpo = $corpo;
  }

}
