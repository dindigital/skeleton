<?php

namespace Site\Models\DataAccess\Entity;

class Settings extends AbstractEntity
{

  public function getMetaDescription ()
  {
    return $this->getDescription;

  }

  public function getMetaTitle ()
  {
    return $this->getTitle();

  }

  public function getHomeTitle ()
  {
    return $this->getField('home_title');

  }

  public function getHomeDescription ()
  {
    return $this->getField('home_description');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }


  public function getContactAddress ()
  {
    return $this->getField('contact_address');

  }

  public function getContactAddressLatitude ()
  {
    return $this->getField('contact_address_latitude');

  }

  public function getContactAddressLongitude ()
  {
    return $this->getField('contact_address_longitude');

  }

  public function getContactPhone ()
  {
    return $this->getField('contact_phone');

  }

  public function getContactEmail ()
  {
    return $this->getField('contact_email');

  }

  public function getFormContact ()
  {
    return $this->getField('form_contact');

  }

  public function getGoogleAnalytcs ()
  {
    return $this->getField('google_analytcs');

  }

  public function getAbout ()
  {
    return $this->getField('about');

  }

}
