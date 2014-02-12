<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Report\Excel\ExportExcel;

/**
 *
 * @package app.models
 */
class MailingExportModel extends BaseModelAdm
{

  public function getXls ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $arrFilters['name'] . '%',
        'email LIKE ?' => '%' . $arrFilters['email'] . '%',
    );

    if ( $arrFilters['mailing_group'] != '' && $arrFilters['mailing_group'] != '0' ) {
      $arrCriteria['id_mailing_group = ?'] = $arrFilters['mailing_group'];
    }

    $select = new Select('mailing');
    $select->addField('name');
    $select->addField('email');
    $select->where($arrCriteria);
    $select->order_by('email');
    $select->group_by('a.id_mailing');

    $select->inner_join('id_mailing', Select::construct('r_mailing_mailing_group'));

    $result = $this->_dao->select($select);


    $xls = new ExportExcel('mailing_' . date('ymd-His'));
    $xls->setResult($result);
    $xls->setTitles(array(
        'Nome',
        'E-mail',
    ));
    $xls->export();
  }

}
