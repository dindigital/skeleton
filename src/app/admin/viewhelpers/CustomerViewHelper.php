<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

class CustomerViewHelper
{

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['name'] = Html::scape($arrFilters['name']);
    $arrFilters['email'] = Html::scape($arrFilters['email']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['name'] = Html::scape($row['name']);
    $row['document'] = Html::scape($row['document']);
    $row['email'] = Html::scape($row['email']);
    $row['address_postcode'] = Html::scape($row['address_postcode']);
    $row['address_street'] = Html::scape($row['address_street']);
    $row['address_number'] = Html::scape($row['address_number']);
    $row['address_complement'] = Html::scape($row['address_complement']);
    $row['address_state'] = Html::scape($row['address_state']);
    $row['address_city'] = Html::scape($row['address_city']);
    $row['phone_ddd'] = Html::scape($row['phone_ddd']);
    $row['phone_number'] = Html::scape($row['phone_number']);

    return $row;
  }

}
