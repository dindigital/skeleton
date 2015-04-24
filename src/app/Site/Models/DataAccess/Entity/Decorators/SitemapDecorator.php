<?php

namespace Site\Models\DataAccess\Entity\Decorators;

class SitemapDecorator extends AbstractDecorator
{

  public function __construct ( \Site\Models\DataAccess\Entity\Sitemap $entity )
  {
    $this->_entity = $entity;

  }

  public function getLoc ()
  {
    return URL . parent::getLoc();

  }

  public function getLastmod ()
  {
    $date = new \DateTime(parent::getLastmod());
    $date->setTimezone(new \DateTimeZone('America/Sao_Paulo'));

    return $date->format(\DateTime::ATOM);

  }

}
