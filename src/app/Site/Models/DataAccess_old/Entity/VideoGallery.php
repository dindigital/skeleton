<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class VideoGallery extends AbstractEntity
{

  public function getIdVideo ()
  {
    return $this->getField('id_video');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getFile ()
  {
    return $this->getField('file');

  }

  public function getIdYoutube ()
  {
    return $this->getField('id_youtube');

  }

  public function getIdVimeo ()
  {
    return $this->getField('link_vimeo');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

  public function getContent ()
  {
    return $this->getField('content');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

  public function getUpdDate ()
  {
    return $this->getField('upd_date');

  }

}
