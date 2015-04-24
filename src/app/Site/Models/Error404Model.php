<?php

namespace Site\Models;

use Site\Helpers\Metatags\Metatags;

class Error404Model extends BaseModelSite
{

    public function getPage ()
    {

        $this->setSettings();
        $this->setMetatag();

        return $this->_return;

    }

    private function setMetatag ()
    {

        $page_metatags = new Metatags\CreateMetatag();
        $page_metatags->setTitle('Erro 404');
        $page_metatags->setDescription('');
        $page_metatags->setKeywords('');

        $metatags = new Metatags\Metatags($page_metatags);

        $this->_return['metatags'] = $metatags;

    }

}
