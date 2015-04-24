<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Standpoint extends AbstractEntity
{

  public function getIdStandpoint ()
  {
    return $this->getField('id_standpoint');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getUpdDate ()
  {
    return $this->getField('upd_date');

  }

  public function getUri ()
  {
    return $this->getField('uri');

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

  public function getContent ()
  {
    return $this->getField('content');

  }

  public function getAuthorName ()
  {
    return $this->getField('author_name');

  }

  public function getAuthorCover ()
  {
    return $this->getField('author_cover');

  }

  public function getAuthorRole ()
  {
    return $this->getField('author_role');

  }

  public function getCoverCredit ()
  {
    return $this->getField('cover_credit');

  }

  public function getCoverDescription ()
  {
    return $this->getField('cover_description');

  }

  public function getContactEmail ()
  {
    return $this->getField('contact_email');

  }

  public function getContactFacebook ()
  {
    return $this->getField('contact_facebook');

  }

  public function getContactTwitter ()
  {
    return $this->getField('contact_twitter');

  }

  public function getContactGoogle ()
  {
    return $this->getField('contact_google');

  }

  public function getContactInstagram ()
  {
    return $this->getField('contact_instagram');

  }

  public function getContactBlog ()
  {
    return $this->getField('contact_blog');

  }

}
