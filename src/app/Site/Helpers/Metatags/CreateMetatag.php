<?php

namespace Site\Helpers\Metatags;

class CreateMetatag
{

  protected $_data = array();

  public function setTitle ( $title )
  {
    $this->_data['title'] = $title;

  }

  public function setDescription ( $description )
  {
    $this->_data['description'] = $description;

  }

  public function setKeywords ( $keywords )
  {
    $this->_data['keywords'] = $keywords;

  }

  public function setImage ( $image )
  {
    $this->_data['image'] = $image;

  }

  public function setImageUrl ( $image_url )
  {
    $this->_data['image_url'] = $image_url;

  }

  public function setArticleType ( $author, $section, $date )
  {
    $this->_data['type'] = 'article';
    $this->_data['type_author'] = $author;
    $this->_data['type_section'] = $section;
    $this->_data['type_date'] = date('Y-m-d\TH:i:sO', strtotime($date));

  }

  public function setUri ( $uri )
  {
    $this->_data['uri'] = $uri;

  }

  public function __get ( $name )
  {
    if ( isset($this->_data[$name]) )
      return $this->_data[$name];

  }

}
