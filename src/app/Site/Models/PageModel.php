<?php

namespace Site\Models;

use Site\Models\DataAccess;
use Site\Helpers\Metatags;
use Site\Models\Entities\Decorators;
use Din\Http\Header;

class PageModel extends BaseModelSite
{

  public function pageView ( $cat, $uri )
  {
    $page_dao = new DataAccess\Page;
    $pages = $page_dao->getByFilter(array(
        'uri' => "/$cat/$uri/"
    ));

    if ( !count($pages) )
      Header::redirect('/404/');

    $page = new Decorators\PageView($pages[0]);
    $metatags = new Metatags($page, $this->getSettings());

    $this->_return['page'] = $page;
    $this->_return['metatags'] = $metatags;

    return $this->_return;

  }

}
