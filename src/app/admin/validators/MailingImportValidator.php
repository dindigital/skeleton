<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;
use finfo;
use Exception;

class MailingImportValidator
{

  public function setMailingGroup ( $mailing_group )
  {
    if ( trim($mailing_group) == '' )
      JsonException::addException('Pelo menos 1 grupo é obrigatório');
  }

  public function setXls ( $file )
  {
    //Array de upload vazio
    if ( !isset($file [0]) )
      throw new Exception('Array de upload vazio');

    $file = $file[0];

    //Array de upload não possui os índices necessários: tmp_name, name
    if ( !(count($file) == 2 && isset($file['tmp_name']) && isset($file['name'])) )
      throw new Exception('Array de upload não possui os índices necessários: tmp_name, name');

    //Pega nome temporário e nome verdadeiro do arquivo
    $tmp_name = $file['tmp_name'];
    $name = $file['name'];

    $origin = 'tmp' . DIRECTORY_SEPARATOR . $tmp_name;

    //Verifica se o arquivo temporário existe
    if ( !is_file($origin) )
      throw new Exception('O arquivo temporário de upload não foi encontrado, certifique-se de dar permissão a pasta tmp');

    //Valida extensão
    $current_ext = pathinfo($name, PATHINFO_EXTENSION);
    $valid_ext = array(
        'xls',
        'xlsx',
    );
    if ( !in_array(strtolower($current_ext), $valid_ext) )
      throw new Exception('Extensão de arquivo deve ser xls ou xlsx');

    //Valida mime type
    $finfo = new finfo(FILEINFO_MIME);
    $current_mime = $finfo->file($origin);
    unset($finfo);

    $valid_mimes = array(
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=binary',
        'application/vnd.ms-excel; charset=binary',
        'application/vnd.ms-office; charset=binary',
        'application/zip; charset=binary',
    );

    if ( !in_array($current_mime, $valid_mimes) )
      throw new Exception('Cabeçalho de arquivo não permitido: ' . $current_mime);
  }

  public function throwException ()
  {
    JsonException::throwException();
  }

}
