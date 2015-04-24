<?php

namespace Site\Models\DataAccess\Entity;

class PollOption extends AbstractEntity
{

  public function getIdPollOption ()
  {
    return $this->getField('id_poll_option');

  }

  public function getOption ()
  {
    return $this->getField('option');

  }

}
