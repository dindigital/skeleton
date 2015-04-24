<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\Multimedia;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;

class MultimediaInformacut extends AbstractDecorator
{

  public function __construct ( Multimedia $entity )
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

  protected function getCoverUri ()
  {
    if ( !is_null($this->_entity->getCover()) )
      return $this->_entity->getCover();

    if ( $this->_entity->getTbl() == 'audio' )
      return '/site/img/img_audio_sample.png';

  }

  protected function getCoverSource ()
  {
    if ( is_null($this->_entity->getCover()) && $this->_entity->getIdYoutube() )
      return 'youtube';

  }

  protected function getYoutubeCover ()
  {
    return "http://img.youtube.com/vi/{$this->_entity->getIdYoutube()}/0.jpg";

  }

  public function getCover ()
  {
    if ( $this->getCoverSource() == 'youtube' )
      return $this->getYoutubeCover();

    $picuri = new Picuri($this->getCoverUri());
    $picuri->setWidth(221);
    $picuri->setHeight(140);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('path');

    $picuri->save();

    return URL . $picuri->getImage();

  }

  public function getUri ()
  {
    return URL . $this->_entity->getUri();

  }

}
