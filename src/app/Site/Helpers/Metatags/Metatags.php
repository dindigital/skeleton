<?php

namespace Site\Helpers\Metatags;

use Din\Http\Header;
use Din\Image\Picuri;

class Metatags
{

  protected $_content;
  protected $_image = null;
  protected $_image_width;
  protected $_image_height;
  protected $_settings_title = null;

  function __construct ( CreateMetatag $content )
  {
    $this->_content = $content;

  }

  public function setSettingsTitle ( $title )
  {
    $this->_settings_title = \Din\Filters\String\Html::scape($title);

  }

  public function getTitle ()
  {
    $title = is_null($this->_settings_title) ? $this->_content->title : $this->_content->title . ' - ' . $this->_settings_title;

    return $title;

  }

  public function getOgTitle ()
  {
    $title = $this->_content->title;

    return htmlspecialchars($title, ENT_COMPAT);

  }

  public function getKeywords ()
  {
    return htmlspecialchars($this->_content->keywords, ENT_COMPAT);

  }

  public function getDescription ()
  {
    return htmlspecialchars($this->_content->description, ENT_COMPAT);

  }

  public function getAuthor ()
  {
    /* $author = 'DIN DIGITAL';
      if ( defined(AGENCY) ) {
      $author = AGENCY;
      } */

    return $this->getName();

  }

  public function getLocale ()
  {
    return 'pt_BR';

  }

  public function getUrl ()
  {

    $uri = !is_null($this->_content->uri) ? $this->_content->uri : Header::getUri();

    return URL . $uri;

  }

  public function getName ()
  {
    $name = CLIENTNAME;

    return $name;

  }

  public function isImageUrl ()
  {
    return (!is_null($this->_content->image_url));

  }

  public function getImage ()
  {
    if ( $this->isImageUrl() )
      return $this->_content->image_url;

    $this->_image = $this->_content->image;

    if ( is_null($this->_content->image) ) {
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

  public function getType ()
  {

    $type = !is_null($this->_content->type) ? $this->_content->type : 'website';

    return $type;

  }

  public function getTypeAuthor ()
  {
    return $this->_content->type_author;

  }

  public function getSection ()
  {
    return $this->_content->type_section;

  }

  public function getDate ()
  {
    return $this->_content->type_date;

  }

}
