<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Respect\Validation\Validator as v;
use Din\Exception\JsonException;

class SettingsValidator extends BaseValidator
{

  public function setHomeTitle ( $home_title )
  {
    if ( !v::string()->notEmpty()->validate($home_title) )
      JsonException::addException('Title Home é obrigatório');

    $this->_table->home_title = $home_title;
  }

  public function setHomeDescription ( $home_description )
  {
    if ( !v::string()->notEmpty()->validate($home_description) )
      JsonException::addException('Description Home é obrigatório');

    $this->_table->home_description = $home_description;
  }

  public function setHomeKeywords ( $home_keywords )
  {
    if ( !v::string()->notEmpty()->validate($home_keywords) )
      JsonException::addException('Keywords Home é obrigatório');

    $this->_table->home_keywords = $home_keywords;
  }

  public function setTitle ( $title )
  {
    if ( !v::string()->notEmpty()->validate($title) )
      JsonException::addException('Title Internas é obrigatório');

    $this->_table->title = $title;
  }

  public function setDescription ( $description )
  {
    if ( !v::string()->notEmpty()->validate($description) )
      JsonException::addException('Description Internas é obrigatório');

    $this->_table->description = $description;
  }

  public function setKeywords ( $keywords )
  {
    if ( !v::string()->notEmpty()->validate($keywords) )
      JsonException::addException('Keywords Internas é obrigatório');

    $this->_table->keywords = $keywords;
  }

}
