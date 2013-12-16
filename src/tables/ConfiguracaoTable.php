<?php

namespace src\tables;

use Din\DataAccessLayer\Table\AbstractTable;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class ConfiguracaoTable extends AbstractTable implements iTable
{

  /**
   * @var int(11) NOT NULL pk
   */
  private $id_configuracao;

  /**
   * @var varchar(255) NOT NULL
   */
  private $title_home;

  /**
   * @var text NOT NULL
   */
  private $description_home;

  /**
   * @var text NOT NULL
   */
  private $keywords_home;

  /**
   * @var varchar(255) NOT NULL title
   */
  private $title_interna;

  /**
   * @var text NOT NULL
   */
  private $description_interna;

  /**
   * @var text NOT NULL
   */
  private $keywords_interna;

  /**
   * @var int(11) NOT NULL
   */
  private $qtd_horas;

  /**
   * @var varchar(255) NOT NULL
   */
  private $email_avisos;

}
