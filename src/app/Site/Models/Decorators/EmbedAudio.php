<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\AudioGallery;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;
use Site\Helpers\AudioPlayer\AudioPlayerClient;
use Site\Helpers\HTMLContent;

class EmbedAudio extends OpenAudio
{

  public function getPlayer ()
  {
    $player = new AudioPlayerClient;
    $player->setId($this->getIdAudio());

    if ( $this->getHasSc() )
      return $player->getPlayerSoundcloudMed($this->getTrackId(), '100%', '100');
    elseif ( $this->getFile() )
      return $player->getPlayerFile($this->getFile(), $this->getTitle());

  }

}
