<?php

namespace Site\Models;

use Site\Helpers\Metatags\Metatags;
use Site\Helpers\Metatags\EmptyMetatag;

class IndexModel extends BaseModelSite
{

    public function getPage ()
    {
        $this->setSettings();
        $this->setMetatag();

        return $this->_return;

    }

    public function setMetatag ()
    {
        $settings = $this->_return['settings'];
        $context = new EmptyMetatag($settings->getHomeTitle(), $settings->getHomeDescription(), $settings->getHomeKeywords());
        $this->_return['metatags'] = new Metatags($context);

    }

}
