<?php

namespace Site\Models\Entities\Decorators;

use Site\Models\Entities\Decorators\AbstractDecorator;
use Site\Helpers\MetatagsInterface;

abstract class AbstractMetatags extends AbstractDecorator implements MetatagsInterface
{

  public function getMetaTitle ()
  {
    return parent::getTitle();

  }

  public function getMetaDescription ()
  {
    return parent::getDescription();

  }

  public function getMetaKeywords ()
  {
    return parent::getKeywords();

  }

}
