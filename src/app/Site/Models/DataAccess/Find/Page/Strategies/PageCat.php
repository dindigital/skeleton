<?php

namespace Site\Models\DataAccess\Find\Page\Strategies;

use Site\Models\DataAccess\Find\Page\StrategyInterface;

class PageCat implements StrategyInterface
{

  public function getTableName ()
  {
    return 'page_cat';

  }

  public function getIdName ()
  {
    return 'id_page_cat';

  }

}
