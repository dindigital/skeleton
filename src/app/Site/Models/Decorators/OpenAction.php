<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\Action;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\HTMLContent;
use Din\Image\Picuri;

class OpenAction extends AbstractDecorator
{

  public function __construct ( Action $entity )
  {
    parent::__construct($entity);

  }

  public function getId() {
    return $this->_entity->getIdAction();
  }

  public function getTbl() {
    return 'action';
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
    $picuri->setWidth(137);
    $picuri->setHeight(200);
    $picuri->setCrop(true);
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle()
    ));

    $picuri->save();

    return $picuri->getImage();

  }

  public function getActionFiles ()
  {
    return new \Site\Models\DataAccess\Collection\Decorators\ActionFileListCollection($this->_entity->getActionFiles());

  }

  public function getCountActionFiles ()
  {
    return count($this->getActionFiles());

  }

}
