<?php

namespace Site\Models\DataAccess\Find\RSS;

interface AreaInterface
{

  /**
   * @return \Din\DataAccessLayer\Select\Select
   */
  public function getSelect ();
}
