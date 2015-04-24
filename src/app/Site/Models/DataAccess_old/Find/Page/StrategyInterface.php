<?php

namespace Site\Models\DataAccess\Find\Page;

interface StrategyInterface
{

  public function getIdName ();

  public function getTableName ();
}
