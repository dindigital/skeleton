<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class SecretariatManager extends AbstractEntity
{

  public function getIdSecretaryManager ()
  {
    return $this->getField('id_secretary_manager');

  }

  public function getIdSecretary ()
  {
    return $this->getField('id_secretary');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getBiography ()
  {
    return $this->getField('biography');

  }

  public function getStartDate ()
  {
    return $this->getField('start_date');

  }

  public function getEndDate ()
  {
    return $this->getField('end_date');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getCoverCredit ()
  {
    return $this->getField('cover_credit');

  }

  public function getCoverDescription ()
  {
    return $this->getField('cover_description');

  }

  public function getState ()
  {
    return $this->getField('state');

  }

  public function getCategory ()
  {
    return $this->getField('category');

  }

  public function getEmail ()
  {
    return $this->getField('email');

  }

  public function getFacebook ()
  {
    return $this->getField('facebook');

  }

  public function getTwitter ()
  {
    return $this->getField('twitter');

  }

  public function getGoogle ()
  {
    return $this->getField('google');

  }

  public function getInstagram ()
  {
    return $this->getField('instagram');

  }

  public function getBlog ()
  {
    return $this->getField('blog');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

}
