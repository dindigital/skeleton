<?php

namespace src\app\admin\models\Log;

use src\app\admin\validators\LogValidator;
use src\app\admin\models\Log\LogAbstract;
use src\app\admin\models\Log\LogInterface;

class LogMySQLModel extends LogAbstract implements LogInterface
{

  public static function save ( $dao, $usuario, $action, $msg, $table, $tableHistory )
  {
    $log = new self;
    $log->_dao = $dao;
    $log->usuario = $usuario;
    $log->msg = $msg;
    $log->table = $table;
    $log->tableHistory = $tableHistory;

    $log->logicSave($action);
  }

  public function insert ( $table_name )
  {
    $validator = new LogValidator();
    $validator->setAdministrador($this->usuario['nome']);
    $validator->setTabela($table_name);
    $validator->setAcao('C');
    $validator->setDescricao($this->msg);
    $validator->setConteudo(json_encode($this->table->setted_values));
    $validator->setIncData();

    $this->_dao->insert($validator->getTable());
  }

  public function update ( $table_name )
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
    $validator->setTabela($table_name);
    $validator->setAcao('U');
    $validator->setDescricao($this->msg);
    $validator->setConteudo($conteudo);
    $validator->setIncData();

    $this->_dao->insert($validator->getTable());
  }

  public function deleteRestore ( $table_name, $action )
  {
    $validator = new LogValidator();
    $validator->setAdministrador($this->usuario['nome']);
    $validator->setTabela($table_name);
    $validator->setAcao($action);
    $validator->setDescricao($this->msg);
    $validator->setConteudo(json_encode($this->tableHistory));
    $validator->setIncData();

    $this->_dao->insert($validator->getTable());
  }

}
