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

  public function setTitle ( $title )
  {
    if ( !v::string()->notEmpty()->validate($title) )
      return JsonException::addException('Titulo é obrigatório');

    if ( !v::string()->length(1, 255)->validate($title) )
      return JsonException::addException('Titulo pode ter no máximo 255 caracteres.');

    $this->_table->title = $title;
  }

  public function setDate ( $date )
  {
    if ( !DateToSql::validate($date) )
      return JsonException::addException('Date é obrigatório');

    $date = DateToSql::filter_date($date);

    $this->_table->date = $date;
  }

  public function setDescription ( $description )
  {

    if ( !v::string()->notEmpty()->validate($description) )
      return JsonException::addException('Descrição é obrigatório');

    if ( !v::string()->length(1, 65535)->validate($description) )
      return JsonException::addException('Descrição pode ter no máximo 65535 caracteres.');

    $this->_table->description = $description;
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
