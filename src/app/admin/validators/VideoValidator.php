<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Din\Filters\Date\DateToSql;

class VideoValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('video');
  }

  public function setTitulo ( $titulo )
  {
    if ( $titulo == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->titulo = $titulo;
  }

  public function setData ( $data )
  {
    if ( !DateToSql::validate($data) )
      return JsonException::addException('Data é obrigatório');

    $data = DateToSql::filter_date($data);

    $this->_table->data = $data;
  }

  public function setDescricao ( $descricao )
  {
    $this->_table->descricao = $descricao;
  }

  public function setLinkYouTube ( $link_youtube )
  {
    $this->_table->link_youtube = $link_youtube;
  }

  public function setLinkVimeo ( $link_vimeo )
  {
    $this->_table->link_vimeo = $link_vimeo;
  }

}
