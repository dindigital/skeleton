<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
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

  public function validate_insert ( $input )
  {
    $this->setNewId();
    $this->_table->id_survey = $input['id_survey'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setRequiredString('question', 'Questão');
  }

  public function validate_update ( $input )
  {
    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setRequiredString('question', 'Questão');
  }

  public function insert ()
  {
    $this->dao_insert(false);
  }

  public function update ()
  {
    $this->dao_update(false);
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
