<?php

namespace Site\Models;

use Site\Models\Entities\Decorators;
use Site\Models\DataAccess;
use Site\Helpers\Metatags;
use Din\Http\Header;

class PageCatModel extends BaseModelSite
{

  public function pageCatView ( $uri )
  {

    $page_cat_dao = new DataAccess\PageCat;
    $page_cats = $page_cat_dao->getPageCat($uri);

    if ( !count($page_cats) ) {
      Header::redirect('/404/');
    }

    $page_cat = new Decorators\PageCatView($page_cats[0]);

    $settings = $this->getSettings();

    $metatags = new Metatags($page_cat, $settings);

    $this->_return['metatags'] = $metatags;
    $this->_return['page'] = $page_cat;

    return $this->_return;

  }

  public function pageSitemap ()
  {
    $page_cat_dao = new DataAccess\PageCat;
    $result = $page_cat_dao->getPageSitemap();

    $sitemap = array();
    foreach ( $result as $page ) {
      $pageDecorator = new Decorators\Sitemap($page);
      if ( !is_null($pageDecorator->getLink()) ) {
        $sitemap[] = $pageDecorator;
      }
    }

    $return = array('sitemap' => $sitemap);

    return $return;

  }

}
