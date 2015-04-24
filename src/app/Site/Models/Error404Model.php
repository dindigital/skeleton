<?php

namespace Site\Models;

use Site\Helpers\Metatags;

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

        $context = new Metatags\EmptyMetatag('Erro 404', 'Página não encontrada', '');
        $this->_return['metatags'] = new Metatags\Metatags($context);

    }

}
