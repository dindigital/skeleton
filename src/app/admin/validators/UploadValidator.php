<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;
use finfo;

class UploadValidator extends BaseValidator
{

  public function validateFile ( $fieldname, array $extensions = array(), array $mimes = array() )
  {
    $file = $this->getValue($fieldname);

    if ( !isset($file [0]) )
      return; //Array de upload vazio

    $file = $file[0];

    if ( !(count($file) == 2 && isset($file['tmp_name']) && isset($file['name'])) )
      return; //Array de upload não possui os índices necessários: tmp_name, name

    if ( !is_writable('public/system') )
      return JsonException::addException('A pasta public/system precisa ter permissão de escrita');


    $tmp_name = $file['tmp_name'];
    $name = $file['name'];

    $origin = 'tmp' . DIRECTORY_SEPARATOR . $tmp_name;

    if ( !is_file($origin) )
      return JsonException::addException('O arquivo temporário de upload não foi encontrado, certifique-se de dar permissão a pasta tmp');

    //Valida extensão
    if ( count($extensions) ) {
      $current_ext = pathinfo($name, PATHINFO_EXTENSION);
      if ( !in_array(strtolower($current_ext), $extensions) )
        return JsonException::addException('Extensão de arquivo deve ser ' . implode(', ', $extensions));
    }

    //Valida mime type
    if ( count($mimes) ) {
      $finfo = new finfo(FILEINFO_MIME);
      $current_mime = $finfo->file($origin);
      unset($finfo);

      if ( !in_array($current_mime, $mimes) )
        throw new Exception('Cabeçalho de arquivo não permitido: ' . $current_mime);
    }

    return true;
  }

}
