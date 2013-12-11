<?php

namespace src\app\teste\models;

use Din\DataAccessLayer\Select;
use Din\Mvc\Model\BaseModel;
use src\tables\AgendaTable;

class AgendaModel extends BaseModel
{

  public function getAll ()
  {
    $select = new Select('agenda');
    $select->addField('*');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function inserts ()
  {
    $this->_dao->execute('ALTER TABLE agenda AUTO_INCREMENT=1');

    $arr_id = array();

    for ( $x = 1; $x <= 15; $x ++ ) {
      $titulo = "Agenda {$x}";
      $descricao = "Descrição {$x}";
      $inc_data = date('Y-m-d H:i:s');

      $table = new AgendaTable();
      $table->titulo = $titulo;
      $table->descricao = $descricao;
      $table->inc_data = $inc_data;

      $arr_id[] = $this->_dao->insert($table);
    }

    return $arr_id;
  }

  public function delete ()
  {
    return $this->_dao->delete(new AgendaTable, array());
  }

  public function update ()
  {
    $table = new AgendaTable();
    $table->titulo = 'atualizado1';
    $table->descricao = 'atualizado1';

    return $this->_dao->update($table, array());
  }

}
