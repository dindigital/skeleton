<?php

namespace Site\Models\Decorators;

use Site\Models\DataAccess\Entity\Poll;
use Din\Filters\String\Html;
use Din\Filters\String\LimitChars;
use Din\Filters\Date\DateFormat;

class OpenPoll extends AbstractDecorator
{

  public function __construct ( Poll $entity )
  {
    parent::__construct($entity);

  }

  public function getQuestion ()
  {
    return Html::scape(LimitChars::filter($this->_entity->getQuestion(), 100));

  }

  public function getStartDate ()
  {
    return DateFormat::filter_date($this->_entity->getStartDate(), 'd/m/Y');

  }

  public function getEndDate ()
  {
    return DateFormat::filter_date($this->_entity->getEndDate(), 'd/m/Y');

  }

  public function getUrl ()
  {
    return URL . $this->_entity->getUri();

  }

  public function isClosed()
  {
    return $this->_entity->getEndDate() < date('Y-m-d') ? true : false;
  }

}