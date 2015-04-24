<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\Standpoint;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\HTMLContent;
use Din\Image\Picuri;
use Din\Filters\String\LimitChars;

class OpenStandpoint extends AbstractDecorator
{

  public function __construct ( Standpoint $entity )
  {
    parent::__construct($entity);

  }

  public function getId() {
    return $this->_entity->getIdStandpoint();
  }

  public function getTbl() {
    return 'standpoint';
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

  public function getAuthorCover ()
  {
    if ( is_null($this->_entity->getAuthorCover()) )
      return;

    $picuri = new Picuri($this->_entity->getAuthorCover());
    $picuri->setWidth(150);
    $picuri->setHeight(150);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getAuthorName()
    ));


    $picuri->save();

    return $picuri->getImage();

  }

  public function getAuthorCoverPath ()
  {
    return $this->_entity->getAuthorCover();

  }

  public function getCoverCredit ()
  {
    return LimitChars::filter($this->_entity->getCoverCredit(), 30, '');

  }

  public function getHasContact ()
  {
    return (bool) $this->getContactEmail() ||
            $this->getContactFacebook() ||
            $this->GetContactTwitter() ||
            $this->getContactInstagram();

  }

  public function getHasCoverInfo ()
  {
    return (bool) $this->getCoverDescription() ||
            $this->getCoverCredit();

  }

  public function getPrinter ()
  {
    return "/imprimir/standpoint/{$this->_entity->getIdStandpoint()}";

  }

}
