<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class SurveyQuestionModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('survey_question');
  }

  public function batch_validate ( $array_questions )
  {
    foreach ( $array_questions as $question ) {
      $input = array(
          'question' => $question
      );
      
      $v = new InputValidator($input);
      $v->string()->validate('question', 'Questão');
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

      $f = new TableFilter($this->_table, $input);
      $f->newId()->filter('id_survey_question');
      $f->string()->filter('id_survey');
      $f->string()->filter('question');
      $f->string()->filter('sequence');

      $this->_dao->insert($this->_table);
    }
  }

  public function batch_update ( $array_questions )
  {
    foreach ( $array_questions as $id_survey_question => $question ) {
      $input = array(
          'question' => $question
      );

      $f = new TableFilter($this->_table, $input);
      $f->string()->filter('question');

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
