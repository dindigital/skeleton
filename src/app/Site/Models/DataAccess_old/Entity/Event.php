<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Event extends AbstractEntity
{

  public function getIdEvent ()
  {
    return $this->getField('id_event');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getStartDate ()
  {
    return $this->getField('start_date');

  }

  public function getEndDate ()
  {
    return $this->getField('end_date');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getContent ()
  {
    return $this->getField('content');

  }

  public function getPlace ()
  {
    return $this->getField('place');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

  public function getIsLive ()
  {
    return $this->getField('is_live');

  }

}
