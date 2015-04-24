<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Settings extends AbstractEntity
{

    public function getHomeTitle ()
    {
        return $this->getField('home_title');

    }

    public function getHomeDescription ()
    {
        return $this->getField('home_description');

    }

    public function getHomeKeywords ()
    {
        return $this->getField('home_keywords');

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

    public function getGoogleAnalytics ()
    {
        return $this->getField('google_analytics');

    }

    public function getFooterText ()
    {
        return nl2br($this->getField('footer_text'));

    }

    public function getContactEmail ()
    {
        return $this->getField('contact_email');

    }

}
