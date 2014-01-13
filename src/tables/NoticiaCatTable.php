<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class NoticiaCatTable extends AbstractTable implements iTable
{

  /**
   * @var char(32) NOT NULL pk
   */
  protected $id_noticia_cat;

  /**
   * @var varchar(255) NOT NULL title
   */
  protected $titulo;

  /**
   * @var tinyint(4) NOT NULL
   */
  protected $home;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $capa;

  /**
   * @var datetime
   */
  protected $inc_data;

  /**
   * @var tinyint(4) NOT NULL
   */
  protected $del;

  /**
   * @var tinyint(4) NOT NULL
   */
  protected $ativo;

  /**
   * @var datetime DEFAULT NULL
   */
  protected $del_data;

  /**
   * @var int DEFAULT NULL
   */
  protected $ordem;

}
