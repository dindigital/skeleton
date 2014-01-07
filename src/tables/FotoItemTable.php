<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class FotoItemTable extends AbstractTable implements iTable
{

  /**
   * @var char(32) NOT NULL pk
   */
  protected $id_foto_item;

  /**
   * @var char(32) NOT NULL
   */
  protected $id_foto;

  /**
   * @var varchar(255) DEFAULT NULL
   */
  protected $legenda;

  /**
   * @var varchar(255) DEFAULT NULL
   */
  protected $credito;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $arquivo;

  /**
   * @var int(11) NOT NULL
   */
  protected $ordem;

}
