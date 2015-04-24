<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Poll extends AbstractEntity
{

  public function getIdPoll ()
  {
    return $this->getField('id_poll');

  }

  public function getQuestion ()
  {
    return $this->getField('question');

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

}
