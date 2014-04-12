<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\Report\Excel\ImportExcel;
use Admin\Models\MailingModel;
use Exception;
use Admin\Helpers\Form;
use Din\File\Files;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class MailingImportModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('mailing');

  }

  public function import_xls ( $input )
  {
    $extensions = array('xls', 'xlsx');
    $mimes = array(
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=binary',
        'application/vnd.ms-excel; charset=binary',
        'application/vnd.ms-office; charset=binary',
        'application/zip; charset=binary'
    );

    $v = new InputValidator($input);
    $v->string()->validate('mailing_group', 'Grupo');
    $has_file = $v->upload($extensions, $mimes)->validate('xls', 'Arquivo de Mailing');

    if ( !$has_file )
      $v->addException('Arquivo inválido');

    $v->throwException();

    $xls_result = $this->getXlsContents($input['xls']);

    $mailing = new MailingModel;

    $total_inserts = 0;
    $total_fails = 0;

    foreach ( $xls_result as $xls_row ) {
      try {
        $mailing->insert(array(
            'active' => '1',
            'name' => $xls_row['name'],
            'email' => $xls_row['email'],
            'mailing_group' => $input['mailing_group'],
                ), false);

        $total_inserts++;
      } catch (Exception $e) {
        //JsonException::clearExceptions();
        $total_fails++;
        //ignore exceptions
      }
    }

    Files::delete("tmp/{$input['xls'][0]['tmp_name']}");

    $report = "Importou {$total_inserts} emails, {$total_fails} falhas";

    $this->log('I', $report, $mailing->getTable());

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
        'xls_uploader' => Form::Upload('xls', '', 'excel')
    );

    return $row;

  }

}
