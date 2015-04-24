<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class ActionFileVersion extends AbstractEntity
{

  public function getIdActionFileVersion ()
  {
    return $this->getField('id_action_file_version');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getFile ()
  {
    return $this->getField('file');

  }

  public function getHasIssuu ()
  {
    return $this->getField('has_issuu');

  }

  public function getIssuuLink ()
  {
    return $this->getField('issuu_link');

  }

  public function getIssuuDocumentId ()
  {
    return $this->getField('issuu_document_id');

  }

  public function getIssuuName ()
  {
    return $this->getField('issuu_name');

  }

}
