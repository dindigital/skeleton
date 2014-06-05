<?php

namespace Site\Models;

use Site\Models\DataAccess;
use Site\Models\Entities\Decorators;
use Din\Http\Header;
use Site\Helpers\EmptyMetatag;
use Site\Helpers\Metatags;
use Site\Models\Exception as e;
use Site\Models\Log\Log;

class BaseModelSite
{

  protected $_settings;
  protected $_return = array();

  public function __construct ()
  {
    try {
      $this->setSettings();
      $this->addBaseReturn();
    } catch (e\EmptyTableException $e) {
      Log::getSite()->addEmergency($e->getMessage());
      die($e->getMessage());
    }

  }

  protected function addBaseReturn ()
  {
    $this->_return['nav'] = $this->getNav();
    $this->_return['settings'] = $this->getSettings();

  }

  protected function setSettings ()
  {
    $settings_dao = new DataAccess\Settings;
    $settingses = $settings_dao->getSettings();
    if ( !count($settingses) )
      throw new e\EmptyTableException('Tabela de configurações está vazia.');

    $this->_settings = new Decorators\SettingsMeta($settingses[0]);

  }

  public function getSettings ()
  {
    return $this->_settings;

  }

  public function setMetatags ( $title )
  {

    $emptyMetatag = new EmptyMetatag(
            $title
            , $this->_settings->getDescription()
            , $this->_settings->getKeywords());

    $metatags = new Metatags($emptyMetatag, $this->_settings);

    $this->_return['metatags'] = $metatags;

  }

  public function getNav ()
  {

    $page_cat_dao = new DataAccess\PageCat;
    $result = $page_cat_dao->getNav();

    $uri = explode('/', Header::getUri());
    $uri = $uri[1] == '' ? '/' : "/$uri[1]/";

    $nav = array();

    foreach ( $result as $page_cat ) {

      $page_cat = new Decorators\PageCatNav($page_cat);

      $drop = $this->mountDropdown($page_cat);

      $nav[] = array(
          'title' => $page_cat->getTitle(),
          'link' => $page_cat->getLink(),
          'target' => $page_cat->getTarget(),
          'class' => $page_cat->getClass($uri, count($drop)),
          'dropdown' => $drop
      );
    }

    return $nav;

  }

  protected function mountDropdown ( $page_cat )
  {
    $id = $page_cat->getIdPageCat();

    $page_dao = new DataAccess\Page;
    $dropdown = array();
    foreach ( $page_dao->getNav($id) as $page ) {
      $dropdown[] = new Decorators\PageNav($page);
    }

    return $dropdown;

  }

}
