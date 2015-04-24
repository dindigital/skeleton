<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class News extends AbstractEntity
{

  public function getIdNews ()
  {
    return $this->getField('id_news');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getTitleHome ()
  {
    return $this->getField('title_home');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getCoverLegend ()
  {
    return $this->getField('cover_legend');

  }

  public function getCoverCredit ()
  {
    return $this->getField('cover_credit');

  }

  public function getHead ()
  {
    return $this->getField('head');

  }

  public function getBody ()
  {
    return $this->getField('body');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

  public function getAuthor ()
  {
    return $this->getField('author');

  }

  public function getUpdDate ()
  {
    return $this->getField('upd_date');

  }

  public function getType ()
  {
    return $this->getField('type');

  }

  public function getQuoteText ()
  {
    return $this->getField('quote_text');

  }

  public function getQuoteCredit ()
  {
    return $this->getField('quote_credit');

  }

  public function setBody ( $body )
  {
    $this->_fields['body'] = $body;

  }

}
