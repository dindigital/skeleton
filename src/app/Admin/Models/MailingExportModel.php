<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Report\Excel\ExportExcel;

/**
 *
 * @package app.models
 */
class MailingExportModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('mailing');

  }

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

    $total = count($result);
    $this->log('E', "Exportou {$total} e-mails", $this->_table);

    $xls = new ExportExcel('mailing_' . date('ymd-His'));
    $xls->setResult($result);
    $xls->setTitles(array(
        'Nome',
        'E-mail',
    ));
    $xls->export();

  }

}
