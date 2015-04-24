<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class DocumentCat extends AbstractEntity
{

  protected $_document_collection;
  protected $_document_sub_collection;

  public function getIdDocumentCat ()
  {
    return $this->getField('id_document_cat');

  }

  public function getIdParent ()
  {
    return $this->getField('id_parent');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function setDocument ( \Site\Models\DataAccess\Collection\DocumentCollection $document_collection )
  {
    $this->_document_collection = $document_collection;

  }

  public function getDocument ()
  {
    return $this->_document_collection;

  }

  public function setDocumentSub ( \Site\Models\DataAccess\Collection\DocumentCatCollection $document_cat_collection )
  {
    $this->_document_sub_collection = $document_cat_collection;

  }

  public function getDocumentSub ()
  {
    return $this->_document_sub_collection;
    ;

  }

}
