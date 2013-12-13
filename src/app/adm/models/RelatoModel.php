<?

namespace src\app\adm005\models;

use src\models\Relato;
use lib\Paginator\Paginator;
use lib\Validation\Validate;
use src\app\adm005\objects\Arrays;

/**
 *
 * @package app.models
 */
class RelatoModel extends Relato
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Relato');
  }

  public function setNome ( $nome )
  {
    if ( $nome == '' )
      throw new \Exception('Nome é obrigatório');

    $this->_table->nome = $nome;
  }

  public function setComentario ( $comentario )
  {
    if ( $comentario == '' )
      throw new \Exception('Comentario é obrigatório');

    $this->_table->comentario = $comentario;
  }

  public function setData ( $data )
  {
    if ( !Validate::data($data) )
      throw new \Exception('Data deve conter uma data válida');

    $this->_table->data = \lib\Validation\DateTransform::sql_date($data);
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
      throw new \Exception('Relato não encontrado.');

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $arrParams['del'] = '0';

    $SQL = '
    SELECT
      id_relato, ativo, nome, data
    FROM
      ' . $this->_table->getName() . '
    {$strWhere}
    ORDER BY
      data DESC
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $nome, $comentario, $data, $ativo )
  {
    $this->_table->clear();

    $this->setNome($nome);
    $this->setComentario($comentario);
    $this->setData($data);
    $this->setIncData();
    $this->setAtivo($ativo);

    $id = $this->_dao->insert($this->_table);

    return $id;
  }

  public function atualizar ( $id, $nome, $comentario, $data, $ativo )
  {
    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Registro não encontrado.');

    $this->setNome($nome);
    $this->setComentario($comentario);
    $this->setData($data);
    $this->setAtivo($ativo);

    $this->_dao->update($this->_table, $id);

    return $id;
  }

}

