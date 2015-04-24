<?php

namespace Site\Models;

use Site\Helpers\Metatags\Metatags;

class NewsModel extends BaseModelSite
{

    public function getPage ( $uri )
    {
        $this->setSettings();
        $this->setPage($uri);
        $this->setMetatag();

        return $this->_return;

    }

    public function setPage ( $uri )
    {
        $business = new Business\News;
        $business->setUri($uri);

        try {
            $this->_return['news'] = $business->getEntity();
        } catch (Business\Exception\ContentNotFoundException $e) {
            $c = new \Site\Controllers\Error404Controller;
            $c->get();
            exit;
        }

    }

    public function setMetatag ()
    {
        $settings = $this->_return['settings'];
        $page = $this->_return['news'];
        $page_metatags = new Metatags\CreateMetatag();
        $page_metatags->setTitle($page->getTitle());
        $page_metatags->setDescription($page->getDescription());
        $page_metatags->setKeywords($page->getKeywords());
        $page_metatags->setImageUrl(URL . $page->getCover());
        $page_metatags->setUri($page->getUri());


        $metatags = new Metatags\Metatags($page_metatags);
        $metatags->setSettingsTitle($settings->getTitle());

        $this->_return['metatags'] = $metatags;

    }

}
