<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\PhotoGallery;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\HTMLContent;

class OpenPhoto extends AbstractDecorator
{

  public function __construct ( PhotoGallery $entity )
  {
    parent::__construct($entity);

  }

  public function getId ()
  {
    return $this->_entity->getIdPhoto();

  }

  public function getTbl ()
  {
    return 'photo';

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

  public function getCoverPath ()
  {
    return $this->_entity->getCover();

  }

  public function getCover ()
  {
    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(740);
    $picuri->setHeight(460);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('path');

    $picuri->save();

    return $picuri->getImage();

  }

  public function getEmbed ()
  {
    $url = URL . "/embed/foto/{$this->getIdPhoto()}/";

    return '<iframe src="' . $url . '" width="550" height="450" scrolling="no" frameborder="no"></iframe>';

  }

}
