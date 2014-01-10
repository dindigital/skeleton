<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class LogTable extends AbstractTable implements iTable
{

  /**
   * @var int(11) NOT NULL
   */
  protected $id_log;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $administrador;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $tabela;

  /**
   * @var datetime NOT NULL
   */
  protected $inc_data;

  /**
   * @var char(1) NOT NULL
   */
  protected $acao;

  /**
   * @var text
   */
  protected $descricao;

  /**
   * @var mediumtext
   */
  protected $conteudo;

}

