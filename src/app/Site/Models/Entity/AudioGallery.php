<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class AudioGallery extends AbstractEntity
{

  public function getIdAudio ()
  {
    return $this->getField('id_audio');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getFile ()
  {
    return $this->getField('file');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getHasSc ()
  {
    return $this->getField('has_sc') == '1';

  }

  public function getTrackId ()
  {
    return $this->getField('track_id');

  }

  public function getTrackPermalink ()
  {
    return $this->getField('track_permalink');

  }

  public function getContent ()
  {
    return $this->getField('content');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

}
