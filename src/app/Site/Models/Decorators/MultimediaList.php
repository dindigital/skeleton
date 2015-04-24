<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\Multimedia;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;

class MultimediaList extends AbstractDecorator
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

  public function getCategory ()
  {
    return Html::scape($this->_entity->getCategory());

  }

  protected function getCoverUri ()
  {
    if ( !is_null($this->_entity->getCover()) )
      return $this->_entity->getCover();

    if ( $this->_entity->getTbl() == 'audio' )
      return '/site/img/img_audio_sample.png';

  }

  public function getCoverSource ()
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
      return '<img src="' . $this->getYoutubeCover() . '" />';

    $picuri = new Picuri($this->getCoverUri());
    $picuri->setWidth(226);
    $picuri->setHeight(138);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle(),
    ));

    $picuri->save();

    return $picuri->getImage();

  }

  public function getBigCover ()
  {
    if ( $this->getCoverSource() == 'youtube' )
      return '<img src="' . $this->getYoutubeCover() . '" />';

    $picuri = new Picuri($this->getCoverUri());
    $picuri->setWidth(484);
    $picuri->setHeight(319);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle(),
    ));

    $picuri->save();

    return $picuri->getImage();

  }

  public function getClass ()
  {
    return $this->_entity->getTbl(); //HA! por enquanto é isso, mas estou encapsulando por segurança

  }

}
