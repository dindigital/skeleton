<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\VideoTable;
use Din\Exception\JsonException;
use Din\Filters\Date\DateToSql;
use Din\Filters\Date\DateFormat;

class VideoValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new VideoTable();
  }

  public function setIdVideo ()
  {
    $this->_table->id_video = $this->_table->getNewId();

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
