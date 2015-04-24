<?php

namespace Site\Models\Business;

use Site\Models\DataAccess;

class Settings
{

  /**
   *
   * @return \Site\Models\Entities\Settings
   * @throws Exception\SettingsNotFoundException
   */
  public function getSettings ()
  {
    $settings_find = new DataAccess\Find\Settings\Settings;
    $result = $settings_find->getAll();

    if ( !count($result) )
      throw new Exception\SettingsNotFoundException('Settings not found');

    return $result[0];

  }

}
