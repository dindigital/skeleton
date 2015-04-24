<?php

namespace Site\Models;

use Site\Models\Exception as e;
use Site\Models\Log\Log;

class BaseModelSite
{

    protected $_return = array();

    protected function setSettings ()
    {
        try {
            $settings = new Business\Settings;
            $this->_return['settings'] = $settings->getSettings();
        } catch (e\EmptyTableException $e) {
            Log::getSite()->addEmergency($e->getMessage());
            die($e->getMessage());
        }

    }

    protected function setNav ()
    {
        $menu = new Business\Menu;
        $nav = $menu->getMenu();

        $this->_return['nav'] = $nav;

    }

}
