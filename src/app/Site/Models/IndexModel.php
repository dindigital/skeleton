<?php

namespace Site\Models;

use Site\Helpers\EmptyMetatag;
use Site\Helpers\Metatags;
use Site\Models\DataAccess;
use Site\Models\Entities\Decorators;

class IndexModel extends BaseModelSite
{

  public function getIndex ()
  {
    $settings = $this->getSettings();

    $index_metatag = new EmptyMetatag(
            $settings->getHomeTitle()
            , $settings->getHomeDescription()
            , $settings->getHomeKeywords());

    $metatags = new Metatags($index_metatag);

    $this->_return['metatags'] = $metatags;

    $this->setLastNews();

    return $this->_return;

  }

  public function setLastNews ()
  {
    $news_dao = new DataAccess\News;
    $result = $news_dao->getLast();

    foreach ( $result as $index => $news ) {
      $result[$index] = new Decorators\NewsList($news);
    }

    $this->_return['lastnews'] = $result;

  }

}
