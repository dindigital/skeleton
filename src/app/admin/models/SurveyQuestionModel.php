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

  protected $_validator;

  public function __construct ()
  {
    parent::__construct();
    $this->_validator = new validator;
  }

  public function validate_insert ( $info )
  {
    $this->setId($this->_validator->setId($this));
    $this->_validator->setQuestion($info['question']);
    $this->_validator->setIdSurvey($info['id_survey']);
  }

  public function validate_update ( $info )
  {
    $this->_validator->setQuestion($info['question']);
  }

  public function insert ()
  {
    $table = $this->_validator->getTable();
    $this->_dao->insert($table);
    $this->log('C', $table->question, $table);
  }

  public function update ()
  {
    $tableHistory = $this->getById();

    $table = $this->_validator->getTable();
    $this->_dao->update($table, array('id_survey_question = ?' => $this->getId()));
    $this->log('U', $table->question, $table, $tableHistory);
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
