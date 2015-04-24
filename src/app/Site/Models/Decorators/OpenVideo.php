<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\VideoGallery;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\VideoPlayer\VideoPlayerClient;
use Site\Helpers\HTMLContent;

class OpenVideo extends AbstractDecorator
{

  public function __construct ( VideoGallery $entity )
  {
    parent::__construct($entity);

  }

  public function getId ()
  {
    return $this->_entity->getIdVideo();

  }

  public function getTbl ()
  {
    return 'video';

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
      return $this->getYoutubeCover();

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(740);
    $picuri->setHeight(460);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('path');

    $picuri->save();

    return $picuri->getImage();

  }

  public function getPlayer ()
  {
    $player = new VideoPlayerClient;
    $player->setId($this->getIdVideo());
    $player->setCover($this->getCover());
    $player->setTitle($this->getTitle());

    if ( $this->getIdYoutube() )
      return ($player->getPlayerYoutube($this->getIdYoutube(), $this->getTitle()));

    if ( $this->getIdVimeo() )
      return ($player->getPlayerVimeo($this->getIdVimeo(), $this->getTitle()));

    if ( $this->getFile() )
      return ($player->getPlayerFile($this->getFile(), $this->getTitle()));

  }

  public function getEmbed ()
  {
    $url = URL . "/embed/video/{$this->getIdVideo()}/";

    if ( $this->getIdVimeo() )
      return '<iframe src="' . $url . '" width="600" height="464" scrolling="no" frameborder="no"></iframe>';

    return '<iframe src="' . $url . '" width="500" height="313" scrolling="no" frameborder="no"></iframe>';

  }

}
