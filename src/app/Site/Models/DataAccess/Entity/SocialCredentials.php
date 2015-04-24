<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class SocialCredentials extends AbstractEntity
{

  public function getFbAppId ()
  {
    return $this->getField('fb_app_id');

  }

  public function getFbPage ()
  {
    return $this->getField('fb_page');

  }

  public function getTwitterUser ()
  {
    return $this->getField('tw_user');

  }

  public function getDiscusUser ()
  {
    return $this->getField('discus_username');

  }

  public function getLinkTwitter ()
  {
    return $this->getField('link_twitter');

  }

  public function getLinkFacebook ()
  {
    return $this->getField('link_facebook');

  }

  public function getLinkGoogle ()
  {
    return $this->getField('link_google');

  }

  public function getLinkInstagram ()
  {
    return $this->getField('link_instagram');

  }

  public function getLinkFlickr ()
  {
    return $this->getField('link_flickr');

  }

  public function getLinkYoutube ()
  {
    return $this->getField('link_youtube');

  }

  public function getLinkSoundCloud ()
  {
    return $this->getField('link_soundcloud');

  }

  public function getLinkCs ()
  {
    return $this->getField('link_cs');

  }

  public function getLinkIssuu ()
  {
    return $this->getField('link_issuu');

  }

  public function getIssuuKey ()
  {
    return $this->getField('issuu_key');

  }

  public function getIssuuSecret ()
  {
    return $this->getField('issuu_secret');

  }

}
