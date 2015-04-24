<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\Printer;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\HTMLContent;

class OpenPrinter extends AbstractDecorator
{

  public function __construct ( Printer $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getDescription ()
  {
    return nl2br($this->_entity->getDescription());

  }

  public function getDate ()
  {
    return DateFormat::filter_date($this->_entity->getDate(), 'd/m/Y - H:i');

  }

  public function getContent ()
  {

    $htmlContent = new HTMLContent;
    $htmlContent->setHtml($this->_entity->getContent());

    return $htmlContent->getHtml();

  }

}
