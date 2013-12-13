<?

namespace src\app\adm005\models;

use src\models\Caso;
use lib\Paginator\Paginator;
use lib\Validation\Validate;
use src\app\adm005\objects\Arrays;

/**
 *
 * @package app.models
 */
class CasoModel extends Caso
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Case');
    $this->ordemCriteria = array();
    $this->ordemOpcional = false;
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
      throw new \Exception('Caso não encontrado.');

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $arrParams['del'] = '0';

    $SQL = '
    SELECT
      id_caso, ativo, nome, ordem
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ORDER BY
      ordem
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);
    $result = $this->getDropdownOrdem($result);

    return $result;
  }

  public function inserir ( $nome, $logo, $ativo )
  {
    $this->_table->clear();

    $this->setNome($nome);
    $this->setArquivo('logo', $logo, null, true);
    $this->setIncData();
    $this->setAtivo($ativo);
    $this->setOrdem();

    $id = $this->_dao->insert($this->_table);

    $this->_table->clear();
    $this->setArquivo('logo', $logo, $id, true);
    $this->_dao->update($this->_table, $id);

    return $id;
  }

  public function atualizar ( $id, $nome, $logo, $ativo )
  {
    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Registro não encontrado.');

    $this->setNome($nome);
    $this->setAtivo($ativo);
    $this->setArquivo('logo', $logo, $id, false);

    $this->_dao->update($this->_table, $id);

    return $id;
  }

}

