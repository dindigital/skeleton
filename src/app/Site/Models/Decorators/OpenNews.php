<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\News;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\HTMLContent;
use Din\Image\Picuri;

class OpenNews extends AbstractDecorator
{

  public function __construct ( News $entity )
  {
    parent::__construct($entity);

  }

  public function getId() {
    return $this->_entity->getIdNews();
  }

  public function getTbl() {
    return 'news';
  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getDate ()
  {
    return DateFormat::filter_date($this->_entity->getDate(), 'd/m/Y - H:i');

  }

  public function getUpdDate ()
  {
    if ( !is_null($this->_entity->getUpdDate()) )
      return DateFormat::filter_date($this->_entity->getUpdDate(), 'd/m/Y - H:i');

  }

  public function getBody ()
  {

    $htmlContent = new HTMLContent;
    $htmlContent->setHtml($this->_entity->getBody());

    return $htmlContent->getHtml();

  }

  public function getAudio ()
  {
    $title = "Título: {$this->getTitle()}, ";
    $body = "Conteúdo: " . strip_tags($this->_entity->getBody());
    $arrFind = array('&quot;', '&#39;');
    $body = str_replace($arrFind, '', $body);

    return $title . $body;

  }

  public function getPrinter ()
  {
    return "/imprimir/news/{$this->_entity->getIdNews()}";

  }

  public function getCoverPath ()
  {
    return $this->_entity->getCover();

  }

  public function getCover ()
  {
    if ( is_null($this->_entity->getCover()) )
      return;

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(600);
    $picuri->setCrop(false);
    $picuri->setTypeReturn('std');

    $picuri->save();
    $legendSize = $picuri->getImage()->width - 20;

    $r = '<div class="cover_inside" style="width:'.$picuri->getImage()->width.'px;">
      <img src="' . $picuri->getImage()->src . '" />';
    if ( $this->_entity->getCoverCredit() ) {
      $r .= '<p class="cover_description" style="width:'.$legendSize.'px;"><span class="rights_spotlight">' . $this->_entity->getCoverCredit() . ' </span>';
      if ( $this->_entity->getCoverLegend() ) {
        $r .= '<span class="text_inside"> ' . $this->_entity->getCoverLegend() . '</span>';
      }
      $r .='</p>';
    }

    $r .='</div>';

    return $r;

  }

}
