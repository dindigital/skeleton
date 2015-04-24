<?php

namespace Site\Models;

use Site\Helpers\Metatags;
use Din\Http\Header;
use Site\Models\DataAccess;

class Error404Model extends BaseModelSite
{

  public function getPage ()
  {

    $this->setSettings();
    $this->setNav();
    $this->setMetatag();
    $this->setUpperBanner();
    $this->checkMigration();

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

  private function checkMigration ()
  {
    $uri = Header::getUri();

    $finder = new DataAccess\Find\Migration\Redirect;
    $finder->setUri($uri);
    $uri_new = $finder->findUri();

    if ( !is_null($uri_new) ) {
      Header::redirect($uri_new);
    }

  }

}
