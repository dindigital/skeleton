<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;
use src\app\admin\models\PageCatModel;

class PageValidator extends BaseValidator
{

  public function setIdPageCat ( $id_page_cat )
  {
    $pagina_cat = new PageCatModel;
    $count = $pagina_cat->getCount($id_page_cat);
    if ( $count == 0 )
      return JsonException::addException('Menu não encontrado.');

    $this->_table->id_page_cat = $id_page_cat;

    return $this;
  }

  public function setIdParent ( $id_parent )
  {
    if ( count($id_parent) ) {
      $last = end($id_parent);
      if ( $last == '0' ) {
        if ( isset($id_parent[count($id_parent) - 2]) ) {
          $this->_table->id_parent = $id_parent[count($id_parent) - 2];
        }
      } else {
        $this->_table->id_parent = end($id_parent);
      }
    }

    return $this;
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
