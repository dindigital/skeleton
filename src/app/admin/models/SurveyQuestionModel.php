<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\StringValidator;
use src\app\admin\helpers\TableFilter;

/**
 *
 * @package app.models
 */
class SurveyQuestionModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('survey_question');
  }

  public function batch_validate ( $array_questions )
  {
    foreach ( $array_questions as $question ) {
      $input = array(
          'question' => $question
      );

      $str_validator = new StringValidator($input);
      $str_validator->validateRequiredString('question', 'Questão');
    }
  }

  public function batch_insert ( $id_survey, $array_questions )
  {
    foreach ( $array_questions as $sequence => $question ) {
      $input = array(
          'id_survey' => $id_survey,
          'question' => $question,
          'sequence' => $sequence + 1
      );

      $filter = new TableFilter($this->_table, $input);
      $filter->setNewId('id_survey_question');
      $filter->setString('id_survey');
      $filter->setString('question');
      $filter->setString('sequence');

      $this->_dao->insert($this->_table);
    }
  }

  public function batch_update ( $array_questions )
  {
    foreach ( $array_questions as $id_survey_question => $question ) {
      $input = array(
          'question' => $question
      );

      $filter = new TableFilter($this->_table, $input);
      $filter->setString('question');

      $this->_dao->update($this->_table, array(
          'id_survey_question = ?' => $id_survey_question
      ));
    }
  }

  public function getByIdSurvey ( $id_survey )
  {
    $select = new Select('survey_question');
    $select->addField('id_survey_question');
    $select->addField('question');

    $select->where(array(
        'id_survey = ?' => $id_survey
    ));

    $select->order_by('sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

}
