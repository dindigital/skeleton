<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess;
use Site\Helpers\HTMLContent;

class Page extends AbstractDecorator
{

  public function __construct ( DataAccess\Entity\Page $entity )
  {
    parent::__construct($entity);

  }

  public function getId() {
    return $this->_entity->getIdpage();
  }

  public function getTbl() {
    return $this->_entity->getArea();
  }

  public function getContent ()
  {

    $htmlContent = new HTMLContent;
    $htmlContent->setHtml($this->_entity->getContent());

    return $htmlContent->getHtml();

  }

  public function getPrinter ()
  {
    return "/imprimir/{$this->_entity->getArea()}/{$this->_entity->getIdpage()}";

  }

}
