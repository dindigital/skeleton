<?

namespace src\tables;

use lib\DataAccessLayer\Table\AbstractTable;
use lib\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class ConfiguracaoTable extends AbstractTable implements iTable
{

  /**
   * @var int(11) NOT NULL pk
   */
  protected $id_configuracao;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $title_home;

  /**
   * @var text NOT NULL
   */
  protected $description_home;

  /**
   * @var text NOT NULL
   */
  protected $keywords_home;

  /**
   * @var varchar(255) NOT NULL title
   */
  protected $title_interna;

  /**
   * @var text NOT NULL
   */
  protected $description_interna;

  /**
   * @var text NOT NULL
   */
  protected $keywords_interna;

  /**
   * @var int(11) NOT NULL
   */
  protected $qtd_horas;

  /**
   * @var varchar(255) NOT NULL
   */
  protected $email_avisos;

}

