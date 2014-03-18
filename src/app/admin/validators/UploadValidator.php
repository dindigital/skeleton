<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class UploadValidator extends BaseValidator2
{

  public function validateFile ( $fieldname )
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

    return true;
  }

}
