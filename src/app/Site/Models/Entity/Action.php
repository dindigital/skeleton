<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;
use Site\Models\DataAccess\Collection\ActionFileCollection;

class Action extends AbstractEntity
{

  protected $_action_files;
  protected $_total_files;

  public function getIdAction ()
  {
    return $this->getField('id_action');

  }

  public function getCatTitle ()
  {
    return $this->getField('cat_title');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getContent ()
  {
    return $this->getField('content');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

  public function getCategory ()
  {
    return $this->getField('category');

  }

  public function getCategoryUri ()
  {
    return $this->getField('category_uri');

  }

  public function setActionFiles ( ActionFileCollection $collection )
  {
    $this->_action_files = $collection;

  }

  public function getActionFiles ()
  {
    return $this->_action_files;

  }

  public function setCountFiles ( $total )
  {
    $this->_total_files = $total;

  }

  public function getCountFiles ()
  {
    if ( !is_null($this->_total_files) )
      return $this->_total_files;

    return count($this->_action_files);

  }

}
