<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class VideoTable extends AbstractTable implements iTable
{

  /**
   * @var char(32) NOT NULL pk
   */
  protected $id_video;

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
  protected $descricao;

  /**
   * @var varchar(255) DEFAULT NULL
   */
  protected $link_youtube;

  /**
   * @var varchar(255) DEFAULT NULL
   */
  protected $link_vimeo;

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
