<?php

namespace Site\Models\Entities\Decorators;

use Site\Models\Entities\Decorators\AbstractMetatags;

class SettingsMeta extends AbstractMetatags
{

  public function getAddress ()
  {
    return nl2br($this->_entity->getAddress());

  }

}
