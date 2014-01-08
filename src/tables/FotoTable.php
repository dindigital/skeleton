<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class FotoTable extends AbstractTable implements iTable
{

  /**
   * @var char(32) NOT NULL pk
   */
  protected $id_foto;

  /**
   * @var varchar(255) NOT NULL title
   */
  protected $titulo;

  /**
   * @var datetime NOT NULL
   */
  protected $data;

  /**
   * @var tinyint(4) NOT NULL
   */
  protected $ativo;

  /**
   * @var datetime
   */
  protected $inc_data;

  /**
   * @var tinyint(4) NOT NULL
   */
  protected $del;

  /**
   * @var datetime DEFAULT NULL
   */
  protected $del_data;

}
