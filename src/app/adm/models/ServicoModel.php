<?

namespace src\app\adm005\models;

use src\models\Servico;
use lib\Paginator\Paginator;
use lib\Validation\Validate;
use src\app\adm005\objects\Arrays;

/**
 *
 * @package app.models
 */
class ServicoModel extends Servico
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Serviço');
  }

  public function setNome ( $nome )
  {
    if ( $nome == '' )
      throw new \Exception('Nome é obrigatório');

    $this->_table->nome = $nome;
  }

  public function getById ( $id )
  {
    $SQL = '
    SELECT
      *
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $id);

    if ( !count($result) )
      throw new \Exception('Serviço não encontrado.');

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $arrParams['del'] = '0';

    $SQL = '
    SELECT
      id_servico, ativo, nome
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ORDER BY
      nome
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $nome, $ativo )
  {
    $this->_table->clear();

    $this->setNome($nome);
    $this->setIncData();
    $this->setAtivo($ativo);

    $id = $this->_dao->insert($this->_table);

    return $id;
  }

  public function atualizar ( $id, $nome, $ativo )
  {
    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Registro não encontrado.');

    $this->setNome($nome);
    $this->setAtivo($ativo);

    $this->_dao->update($this->_table, $id);

    return $id;
  }

  public function getListbox ( $selected = array() )
  {
    return $this->getListbox_base($selected);
  }

}

