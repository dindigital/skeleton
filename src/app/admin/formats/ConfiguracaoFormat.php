<?php

namespace src\app\admin\formats;

class ConfiguracaoFormat
{

  public static function formatRow ( $row )
  {
    $row['title_home'] = htmlspecialchars($row['title_home']);
    $row['description_home'] = htmlspecialchars($row['description_home']);
    $row['keywords_home'] = htmlspecialchars($row['keywords_home']);
    $row['title_interna'] = htmlspecialchars($row['title_interna']);
    $row['description_interna'] = htmlspecialchars($row['description_interna']);
    $row['keywords_interna'] = htmlspecialchars($row['keywords_interna']);

    return $row;
  }

}
