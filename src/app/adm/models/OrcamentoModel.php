<?

namespace src\app\adm005\models;

use lib\Mvc\Model\BaseModel;
use src\tables\PedidoTable;
use lib\Paginator\Paginator;
use lib\Validation\Validate;
use src\app\adm005\objects\Arrays;

/**
 *
 * @package app.models
 */
class OrcamentoModel extends BaseModel
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Orçamento');
    $this->_table = new PedidoTable();
  }

  public function getById ( $id )
  {
    $SQL = '
    SELECT
      a.id_pedido, a.inc_data, a.tipo, a.situacao, a.n_serie, a.qtd, a.descricao,
      b.rz_social cliente,
      c.nome servico,
      d.nome produto,
      e.nome marca
    FROM
      pedido a
    INNER JOIN
      cliente b
    ON
      b.id_cliente = a.id_cliente
    LEFT JOIN
      servico c
    ON
      c.id_servico = a.id_servico
    LEFT JOIN
      produto d
    ON
      d.id_produto = a.id_produto
    LEFT JOIN
      marca e
    ON
      e.id_marca = a.id_marca

    {$strWhere}
    ORDER BY
      a.inc_data DESC
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, array('id_pedido' => $id));

    if ( !count($result) )
      throw new \Exception('Pedido não encontrado.');

    $SQL = '
      SELECT
        a.preco, a.prazo, a.forma_pgto, a.descricao, a.inc_data,
        b.rz_social fornecedor
      FROM
        orcamento a
      INNER JOIN
        fornecedor b
      ON
        b.id_fornecedor = a.id_fornecedor
      {$strWhere}
      ORDER BY
        a.inc_data DESC
    ';

    $pedido = $result[0];

    $pedido->orcamento = $this->_dao->getByCriteria($this->_table, $SQL, array('id_pedido' => $id));

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $SQL = '
    SELECT
      a.id_pedido, a.inc_data, a.tipo, a.situacao,
      b.rz_social cliente
    FROM
      pedido a
    INNER JOIN
      cliente b
    ON
      b.id_cliente = a.id_cliente
    {$strWhere}
    ORDER BY
      a.inc_data DESC
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $ativo, $rz_social, $cnpj, $nome_contato, $endereco, $onde_conheceu, $qtd_funcionario, $email, $senha )
  {
    $this->_table->clear();

    $this->setRzSocial($rz_social);
    $this->setCnpj($cnpj);
    $this->setNomeContato($nome_contato);
    $this->setEndereco($endereco);
    $this->setOndeConheceu($onde_conheceu);
    $this->setQtdFuncionario($qtd_funcionario);
    $this->setEmail($email);
    $this->setIncData();

    $login = new LoginModel();
    $login->validate_insert($ativo, $email, $senha, 'C');

    $id_cliente = $this->_dao->insert($this->_table);

    $login->setIdCliente($id_cliente);
    $login->inserir();

    return $id_cliente;
  }

  public function atualizar ( $id, $ativo, $rz_social, $cnpj, $nome_contato, $endereco, $onde_conheceu, $qtd_funcionario, $email, $senha )
  {
    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Registro não encontrado.');

    $this->setRzSocial($rz_social);
    $this->setCnpj($cnpj);
    $this->setNomeContato($nome_contato);
    $this->setEndereco($endereco);
    $this->setOndeConheceu($onde_conheceu);
    $this->setQtdFuncionario($qtd_funcionario);
    $this->setEmail($email);

    $login = new LoginModel();
    $id_login = $login->getIdLoginByIdCliente($id);
    $login->validate_update($id_login, $ativo, $email, $senha, 'C');

    $this->_dao->update($this->_table, $id);

    $login->atualizar($id_login);

    return $id;
  }

}

