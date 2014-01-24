<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Din\Filters\Date\DateToSql;
use Respect\Validation\Validator as v;

class VideoValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('video');
  }

  public function setTitulo ( $titulo )
  {
    if ( !v::string()->notEmpty()->validate($titulo) )
      return JsonException::addException('Titulo é obrigatório');

    if ( !v::string()->length(1, 255)->validate($titulo) )
      return JsonException::addException('Titulo pode ter no máximo 255 caracteres.');

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
    if ( !v::string()->length(1, 65535)->validate($descricao) )
      return JsonException::addException('Descrição pode ter no máximo 65535 caracteres.');

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
