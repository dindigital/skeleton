<?php

namespace Site\Helpers\Metatags;

use MetatagsInterface;

class EmptyMetatag implements MetatagsInterface
{

    protected $_title;
    protected $_description;
    protected $_keywords;

    /**
     *
     * @param type $title
     * @param type $description
     * @param type $keywords
     */
    public function __construct ( $title = '', $description = '', $keywords = '' )
    {
        $this->_title = $title;
        $this->_description = $description;
        $this->_keywords = $keywords;

    }

    public function getMetaDescription ()
    {
        return $this->_description;

    }

    public function getMetaKeywords ()
    {
        return $this->_keywords;

    }

    public function getMetaTitle ()
    {
        return $this->_title;

    }

}
