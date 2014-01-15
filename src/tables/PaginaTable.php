<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class PaginaTable extends AbstractTable implements iTable
{

  /**
   * @var char(32) NOT NULL
   */
  protected $id_pagina;

  /**
   * @var char(32) NOT NULL
   */
  protected $id_pagina_cat;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $titulo;

  /**
   * @var mediumtext NOT NULL
   */
  protected $conteudo;

  /**
   * @var text NOT NULL
   */
  protected $description;

  /**
   * @var text NOT NULL
   */
  protected $keywords;

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
