<?php

namespace Site\Models;

use Site\Helpers\PaginatorSite;
use Site\Models\Entities\Decorators;
use Site\Models\DataAccess;
use Site\Helpers\Metatags;
use Site\Helpers\EmptyMetatag;

class NewsModel extends BaseModelSite
{

  public function newsList ( $pag )
  {
    $news_dao = new DataAccess\News;

    $paginator = new PaginatorSite(5, $pag);

    $result = $news_dao->getList($paginator);

    foreach ( $result as $i => $news ) {
      $result[$i] = new Decorators\NewsList($news);
    }

    $metatags = new Metatags(new EmptyMetatag('Notícias'), $this->getSettings());

    $this->_return['metatags'] = $metatags;
    $this->_return['news'] = $result;
    $this->_return['paginator'] = $paginator->getNumbers();

    return $this->_return;

  }

  public function newsView ( $uri )
  {

    $uri = "/noticias/{$uri}/";

    $news_dao = new DataAccess\News;
    $result = $news_dao->getNews($uri);

    $news = new Decorators\NewsView($result[0]);

    $metatags = new Metatags($news, $this->getSettings());
    $metatags->setImage($news->getImage());
    $metatags->setArticleType('DIN DIGITAL', $news->getCategory(), $news->getEntity()->getDate());

    $this->_return['metatags'] = $metatags;
    $this->_return['news'] = $news;

    return $this->_return;

  }

  public function newsSitemap ()
  {
    $news_dao = new DataAccess\News;
    $result = $news_dao->getNewsSitemap();

    foreach ( $result as $index => $news ) {
      $result[$index] = new Decorators\Sitemap($news);
    }

    $return = array('sitemap' => $result);

    return $return;

  }

}
