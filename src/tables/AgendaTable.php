<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class AgendaTable extends AbstractTable implements iTable
{

  /**
   * @var INT NOT NULL pk
   */
  protected $id_agenda;

  /**
   * @var VARCHAR(255) NOT NULL title
   */
  protected $titulo;

  /**
   * @var MEDIUMTEXT NOT NULL
   */
  protected $descricao;

  /**
   * @var DATETIME NOT NULL
   */
  protected $inc_data;

}
