<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\AudioGallery;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\AudioPlayer\AudioPlayerClient;
use Site\Helpers\HTMLContent;

class OpenAudio extends AbstractDecorator
{

  public function __construct ( AudioGallery $entity )
  {
    parent::__construct($entity);

  }

  public function getId ()
  {
    return $this->_entity->getIdAudio();

  }

  public function getTbl ()
  {
    return 'audio';

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getDate ()
  {
    return DateFormat::filter_date($this->_entity->getDate());

  }

  public function getUpdDate ()
  {
    if ( !is_null($this->_entity->getUpdDate()) )
      return DateFormat::filter_date($this->_entity->getUpdDate(), 'd/m/Y - H:i');

  }

  public function getContent ()
  {

    $htmlContent = new HTMLContent;
    $htmlContent->setHtml($this->_entity->getContent());
    return $htmlContent->getHtml();

  }

  public function getPlayer ()
  {
    $player = new AudioPlayerClient;
    $player->setId($this->getIdAudio());

    if ( $this->getHasSc() )
      return $player->getPlayerSoundcloud($this->getTrackId());
    elseif ( $this->getFile() )
      return $player->getPlayerFile($this->getFile(), $this->getTitle());

  }

  public function getEmbed ()
  {
    $url = URL . "/embed/audio/{$this->getIdAudio()}/";

    if ( $this->getHasSc() )
      return '<iframe src="' . $url . '" width="400" height="100" scrolling="no" frameborder="no"></iframe>';
    elseif ( $this->getFile() )
      return '<iframe src="' . $url . '" width="400" height="30" scrolling="no" frameborder="no"></iframe>';

  }

  public function getCoverPath ()
  {
    return $this->_entity->getCover();

  }

}
