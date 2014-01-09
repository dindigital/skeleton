<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class NoticiaTable extends AbstractTable implements iTable
{

  /**
   * @var char(32) NOT NULL pk
   */
  protected $id_noticia;

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
   * @var text NOT NULL
   */
  protected $chamada;

  /**
   * @var mediumtext NOT NULL
   */
  protected $corpo;

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

  /**
   * @var varchar(255) DEFAULT NULL
   */
  protected $capa;

}
