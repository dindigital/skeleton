<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class SurveyQuestionValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('survey_question');
  }

  public function setIdSurvey ( $id_survey )
  {
    $this->_table->id_survey = $id_survey;
  }

  public function setQuestion ( $question )
  {
    if ( !v::string()->notEmpty()->validate($question) )
      return JsonException::addException('Campo questão não pode ficar em branco');

    $this->_table->question = $question;
  }

}
