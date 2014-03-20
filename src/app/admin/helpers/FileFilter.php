<?php

namespace src\app\admin\helpers;

class FileFilter extends TableFilter
{

  // FILTERS ___________________________________________________________________

  public function setLabelCredit ( $field )
  {
    $file = $this->getValue($field);
    $file = 'tmp/' . $file['tmp_name'];

    if ( !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'tiff')) )
      return;

    $exif = exif_read_data($file);

    if ( isset($exif['ImageDescription']) ) {
      $this->_table->label = $exif['ImageDescription'];
    }

    if ( isset($exif['Artist']) ) {
      $this->_table->credit = $exif['Artist'];
    }
  }

}
