<?php

namespace Site\Models\DataAccess\Find\Page\Strategies;

use Site\Models\DataAccess\Find\Page\StrategyInterface;

class PageInd implements StrategyInterface
{

  public function getTableName ()
  {
    return 'page_ind';

  }

  public function getIdName ()
  {
    return 'id_page_ind';

  }

}
