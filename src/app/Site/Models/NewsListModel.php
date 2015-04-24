<?php

namespace Site\Models;

use Site\Helpers\Metatags;
use Site\Helpers\EmptyMetatag;

class NewsListModel extends BaseModelSite
{

    public function getPage ( $pag, $qtd )
    {
        $this->setSettings();
        $this->setFeaturedNewsCarousel();
        $this->setPage($pag, $qtd);
        $this->setMetatag();

        return $this->_return;

    }

    public function setPage ( $pag = 1, $qtd = 10 )
    {
        $business = new Business\NewsList;
        try {
            $business->setCurrentPage($pag);
            $business->setItensPerPage($qtd);
            $this->_return['news_list'] = $business->getCollection();
            $paginator = $business->getPaginator();

            $this->_return['sumary'] = array(
                'total' => $paginator->getTotalPags(),
                'itens_per_page' => $business->getItensPerPage(),
                'offset' => $business->getOffset(),
                'current' => ((intval($pag) == 0) ? 1 : intval($pag))
            );
            $this->_return['paginator'] = $paginator->getNumbers('/noticias/');
        } catch (Business\Exception\ContentNotFoundException $e) {
            $c = new \Site\Controllers\Error404Controller;
            $c->get();
            exit;
        }

    }

    public function setMetatag ()
    {
        $settings = $this->_return['settings'];
        $context = new EmptyMetatag($settings->getTitle(), $settings->getDescription(), $settings->getKeywords());
        $this->_return['metatags'] = new Metatags($context);

    }

    public function setFeaturedNewsCarousel ()
    {
        $business = new Business\News;
        $this->_return['featured_news'] = $business->getFeaturedNewsCarousel();

    }

}
