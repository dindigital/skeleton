<?

namespace src\tables;

use lib\DataAccessLayer\Table\AbstractTable;
use lib\DataAccessLayer\Table\iTable;

/**
 *
 * @package tables
 */
class LoginTable extends AbstractTable implements iTable
{

  /**
   * @var INT(11) NOT NULL pk
   */
  protected $id_login;

  /**
   * @var INT(11) NOT NULL
   */
  protected $id_fornecedor;

  /**
   * @var INT(11) NOT NULL
   */
  protected $id_cliente;

  /**
   * @var VARCHAR(255) NOT NULL login
   */
  protected $usuario;

  /**
   * @var CHAR(32) NOT NULL pass
   */
  protected $senha;

  /**
   * @var CHAR(1) NOT NULL COMMENT 'C = Cliente\nF = Fornecedor\n=============='
   */
  protected $tipo;

  /**
   * @var TINYINT(4) NOT NULL active
   */
  protected $ativo;

}

