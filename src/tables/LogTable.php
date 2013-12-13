<?php

namespace src\tables;

use lib\DataAccessLayer\Table\AbstractTable;
use lib\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class LogTable extends AbstractTable implements iTable
{

  /**
   * @var int(11) NOT NULL pk
   */
  protected $id_log;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $responsavel;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $nome_legivel;

  /**
   * @var int(11) DEFAULT NULL
   */
  protected $id_secao;

  /**
   * @var datetime NOT NULL
   */
  protected $data;

  /**
   * @var char(1) NOT NULL
   */
  protected $crud;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $secao;

  /**
   * @var text
   */
  protected $descricao;

}

