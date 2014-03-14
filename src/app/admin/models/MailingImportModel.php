<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\validators\MailingImportValidator as validator;
use Din\Report\Excel\ImportExcel;
use src\app\admin\models\MailingModel;
use Exception;
use src\app\admin\helpers\Form;

/**
 *
 * @package app.models
 */
class MailingImportModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('mailing');
  }

  public function import_xls ( $info )
  {
    $validator = new validator;
    $validator->setMailingGroup($info['mailing_group']);
    $validator->setXls($info['xls']);
    $validator->throwException();

    $xls_result = $this->getXlsContents($info['xls']);

    $mailing = new MailingModel;

    $total_inserts = 0;
    $total_fails = 0;

    foreach ( $xls_result as $xls_row ) {
      try {
        $mailing->insert(array(
            'name' => $xls_row['name'],
            'email' => $xls_row['email'],
            'mailing_group' => $info['mailing_group'],
                ), false);

        $total_inserts++;
      } catch (Exception $e) {
        $total_fails++;
        //ignore exceptions
      }
    }

    $report = "Importou {$total_inserts} emails, {$total_fails} falhas";

    $this->log('I', $report, 'mailing');

    return $report;
  }

  protected function getXlsContents ( $xls )
  {
    $i = new ImportExcel;
    $i->setFile("tmp/{$xls[0]['tmp_name']}");
    $i->import();

    $r = array();

    foreach ( $i->getData() as $line => $row ) {
      $r[$line]['email'] = $row[0];
      $r[$line]['name'] = $row[1];
    }

    return $r;
  }

  public function createFields ()
  {
    $row = array(
        'xls' => Form::Upload('xls', '', 'excel')
    );

    return $row;
  }

}
