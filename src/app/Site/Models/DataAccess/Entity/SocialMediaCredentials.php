<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class SocialMediaCredentials extends AbstractEntity
{

  public function getIssuuKey ()
  {
    return $this->getField('issuu_key');

  }

  public function getIssuuSecret ()
  {
    return $this->getField('issuu_secret');

  }

  public function getLinkTwitter ()
  {
    return $this->getField('link_twitter');

  }

  public function getLinkInstagram ()
  {
    return $this->getField('link_instagram');

  }

  public function getLinkFacebook ()
  {
    return $this->getField('link_facebook');

  }

  public function getLinkGoogle ()
  {
    return $this->getField('link_google');

  }

  public function getLinkYouTube ()
  {
    return $this->getField('link_youtube');

  }

}
