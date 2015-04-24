<?php

namespace Site\Helpers\Metatags;

use Din\Http\Header;
use Site\Helpers\Metatags\MetatagsInterface;
use Din\Image\Picuri;

class Metatags
{

    protected $_content;
    protected $_settings;
    protected $_image = null;
    protected $_image_width;
    protected $_image_height;
    protected $_type = 'website';
    protected $_type_author;
    protected $_type_section;
    protected $_type_date;

    function __construct ( MetatagsInterface $content, MetatagsInterface $settings = null )
    {
        $this->_content = $content;
        $this->_settings = $settings;

    }

    public function getTitle ()
    {

        return is_null($this->_settings) ? $this->_content->getMetaTitle() :
                $this->_content->getMetaTitle() . ' - ' . $this->_settings->getMetaTitle();

    }

    public function getKeywords ()
    {
        return is_null($this->_settings) ? $this->_content->getMetaKeywords() :
                $this->_content->getMetaKeywords();

    }

    public function getDescription ()
    {
        return is_null($this->_settings) ? $this->_content->getMetaDescription() :
                $this->_content->getMetaDescription();

    }

    public function getAuthor ()
    {
        $author = 'DIN DIGITAL';
        if ( defined(AGENCY) ) {
            $author = AGENCY;
        }

        return $author;

    }

    public function getLocale ()
    {
        return 'pt_BR';

    }

    public function getUrl ()
    {
        return URL . Header::getUri();

    }

    public function getName ()
    {
        $name = '';
        if ( defined(CLIENTNAME) ) {
            $name = CLIENTNAME;
        }

        return $name;

    }

    public function setImage ( $image )
    {

        $this->_image = $image;

    }

    public function getImage ()
    {
        if ( is_null($this->_image) ) {
            $this->_image = DEFAULT_IMAGE;
        }

        $this->_image = Picuri::picUri($this->_image, 1200, 630, false, array(), 'path');

        return URL . $this->_image;

    }

    public function getImageType ()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, PATH_REPLACE . $this->_image);
        finfo_close($finfo);
        return $type;

    }

    protected function getImageSize ()
    {
        list($this->_image_width, $this->_image_height) = getimagesize(PATH_REPLACE . $this->_image);

    }

    public function getImageWidth ()
    {
        $this->getImageSize();
        return $this->_image_width;

    }

    public function getImageheight ()
    {
        return $this->_image_height;

    }

    public function setArticleType ( $author, $section, $date )
    {
        $this->_type = 'article';
        $this->_type_author = $author;
        $this->_type_section = $section;
        $this->_type_date = date('Y-m-d\TH:i:sO', strtotime($date));

    }

    public function getType ()
    {
        return $this->_type;

    }

    public function getTypeAuthor ()
    {
        return $this->_type_author;

    }

    public function getSection ()
    {
        return $this->_type_section;

    }

    public function getDate ()
    {
        return $this->_type_date;

    }

}
