<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\SearchResult;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;

class SearchResultDecorator extends AbstractDecorator
{

  public function __construct ( SearchResult $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getDate ()
  {
    $date = $this->_entity->getDate();
    return DateFormat::filter_date($date);

  }

  public function getWeekday ()
  {
    $date = $this->_entity->getDate();
    return DateFormat::filter_week($date);

  }

  public function getCoverSource ()
  {
    if ( is_null($this->_entity->getCover()) && $this->_entity->getIdYoutube() )
      return 'youtube';

  }

  protected function getYoutubeCover ()
  {
    return "http://img.youtube.com/vi/{$this->_entity->getIdYoutube()}/1.jpg";

  }

  public function getCover ()
  {

    if ( $this->getCoverSource() == 'youtube' )
      return '<img src="' . $this->getYoutubeCover() . '" />';

    if ( is_null($this->_entity->getCover()) )
      return;

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(90);
    $picuri->setHeight(70);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle(),
    ));

    $picuri->save();

    return $picuri->getImage();

  }

}
