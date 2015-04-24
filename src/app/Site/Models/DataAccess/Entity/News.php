<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class News extends AbstractEntity
{

    public function getIdNews ()
    {
        return $this->getField('id_news');

    }

    public function getTitle ()
    {
        return $this->getField('title');

    }

    public function getUri ()
    {
        return $this->getField('uri');

    }

    public function getDate ()
    {
        return $this->getField('inc_date');

    }

    public function getCover ()
    {
        return $this->getField('cover');

    }

    public function getHead ()
    {
        return $this->getField('head');

    }

    public function getBody ()
    {
        return $this->getField('body');

    }

    public function getKeywords ()
    {
        return $this->getField('keywords');

    }

    public function getDescription ()
    {
        return $this->getField('description');

    }

}
