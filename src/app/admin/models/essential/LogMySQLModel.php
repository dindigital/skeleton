<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\LogValidator;
use src\app\admin\models\essential\LogAbstract;
use src\app\admin\models\essential\LogInterface;

class LogMySQLModel extends LogAbstract implements LogInterface
{

  public static function save ( $dao, $usuario, $action, $msg, $name, $table, $tableHistory )
  {
    $log = new self;
    $log->_dao = $dao;
    $log->usuario = $usuario;
    $log->msg = $msg;
    $log->name = $name;
    $log->table = $table;
    $log->tableHistory = $tableHistory;

    $log->logicSave($action);
  }

  public function insert ()
  {
    $validator = new LogValidator();
    $validator->setAdministrador($this->usuario['nome']);
    $validator->setName($this->name);
    $validator->setAcao('C');
    $validator->setDescricao($this->msg);
    $validator->setConteudo(json_encode($this->table->setted_values));
    $validator->setIncData();

    $this->_dao->insert($validator->getTable());
  }

  public function update ()
  {

    $diff = array();

    foreach ( $this->table->setted_values as $key => $value ) {
      if ( $value != $this->tableHistory[$key] ) {
        $diff[$key] = array(
            'before' => $this->tableHistory[$key],
            'after' => $value
        );
      }
    }

    if ( count($diff) ) {
      $conteudo = json_encode($diff);
    } else {
      $conteudo = 'Não houveram alterações';
    }

    $validator = new LogValidator();
    $validator->setAdministrador($this->usuario['nome']);
    $validator->setName($this->name);
    $validator->setAcao('U');
    $validator->setDescricao($this->msg);
    $validator->setConteudo($conteudo);
    $validator->setIncData();

    $this->_dao->insert($validator->getTable());
  }

  public function deleteRestore ( $action )
  {
    $validator = new LogValidator();
    $validator->setAdministrador($this->usuario['nome']);
    $validator->setName($this->name);
    $validator->setAcao($action);
    $validator->setDescricao($this->msg);
    $validator->setConteudo(json_encode($this->tableHistory));
    $validator->setIncData();

    $this->_dao->insert($validator->getTable());
  }

}
