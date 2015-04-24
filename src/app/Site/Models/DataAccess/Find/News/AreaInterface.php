<?php

namespace Site\Models\DataAccess\Find\News;

abstract class AreaInterface extends \Site\Models\DataAccess\Find\AbstractFind
{

  /**
   * @return \Din\DataAccessLayer\Select\Select
   */
  abstract public function getSelect ();
}
