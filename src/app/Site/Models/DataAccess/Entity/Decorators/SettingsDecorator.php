<?php

namespace Site\Models\DataAccess\Entity\Decorators;

class SettingsDecorator extends AbstractDecorator
{

  public function __construct ( \Site\Models\DataAccess\Entity\Settings $entity )
  {
    parent::__construct($entity);

  }

  public function getEmbedMap()
  {
    $address = parent::getContactAddress();
    $latitude = parent::getContactAddressLatitude();
    $longitude = parent::getContactAddressLongitude();

    if (!$address || !$latitude || !$longitude)
        return null;

    return "https://maps.google.com/maps?q={$latitude},{$longitude}&q={$address}&output=embed";

  }

}
