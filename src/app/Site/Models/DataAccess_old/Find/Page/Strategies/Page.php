<?php

namespace Site\Models\DataAccess\Find\Page\Strategies;

use Site\Models\DataAccess\Find\Page\StrategyInterface;

class Page implements StrategyInterface
{

  public function getTableName ()
  {
    return 'page';

  }

  public function getIdName ()
  {
    return 'id_page';

  }

}
