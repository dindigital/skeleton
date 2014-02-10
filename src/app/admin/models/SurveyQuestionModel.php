<?php

namespace src\app\admin\models;

use src\app\admin\validators\SurveyQuestionValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;

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

  public function validate_insert ( $info )
  {
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setQuestion($info['question']);
    $validator->setIdSurvey($info['id_survey']);
  }

  public function validate_update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setQuestion($info['question']);
  }

  public function insert ()
  {
    $this->_dao->insert($this->_table);
    //$this->log('C', $this->_table->question, $this->_table);
  }

  public function update ()
  {
    $tableHistory = $this->getById();

    $this->_dao->update($this->_table, array('id_survey_question = ?' => $this->getId()));
    //$this->log('U', $this->_table->question, $this->_table, $tableHistory);
  }

  public function getByIdSurvey ( $id_survey )
  {
    $select = new Select('survey_question');
    $select->addField('id_survey_question');
    $select->addField('question');

    $select->where(array(
        'id_survey = ?' => $id_survey
    ));

    $result = $this->_dao->select($select);

    return $result;
  }

}
