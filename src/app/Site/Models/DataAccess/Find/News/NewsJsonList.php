<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Entity;
use Site\Models\DataAccess\Collection;

class NewsJsonList
{

  protected $_array = array();

  public function setUrl ( $url )
  {
    $file = file_get_contents($url);
    $json = json_decode($file);

    $this->_array = $json;

  }

  /**
   *
   * @return Collection\NewsCollection
   */
  public function getCollection ()
  {
    $collection = new Collection\NewsCollection;

    foreach ( $this->_array as $item ) {
      $news = new Entity\News;
      $news->setFields((array) $item);

      $itens[] = $news;
    }

    $collection->setItens($itens);

    return $collection;

  }

}
