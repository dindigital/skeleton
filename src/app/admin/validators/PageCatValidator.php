<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;

class PageCatValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('page_cat');
  }

  public function setTitle ( $title )
  {
    if ( $title == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->title = $title;
  }

  public function setContent ( $content )
  {
    $this->_table->content = $content;
  }

  public function setDescription ( $description )
  {
    $this->_table->description = $description;
  }

  public function setKeywords ( $keywords )
  {
    $this->_table->keywords = $keywords;
  }

}