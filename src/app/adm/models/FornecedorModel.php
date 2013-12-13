<?

namespace src\app\adm005\models;

use src\models\Fornecedor;
use lib\Paginator\Paginator;
use lib\DataAccessLayer\Select;
use lib\Validation\Validate;

/**
 *
 * @package app.models
 */
class FornecedorModel extends Fornecedor
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Fornecedor');
  }

  public function setRzSocial ( $rz_social )
  {
    if ( $rz_social == '' )
      throw new \Exception('Razão Social é obrigatório');

    $this->_table->rz_social = $rz_social;
  }

  public function setNomeContato ( $nome_contato )
  {
    if ( $nome_contato == '' )
      throw new \Exception('NomeContato é obrigatório');

    $this->_table->nome_contato = $nome_contato;
  }

  public function setCnpj ( $cnpj )
  {
    if ( $cnpj == '' )
      throw new \Exception('Cnpj é obrigatório');

    $this->_table->cnpj = $cnpj;
  }

  public function setLogradouro ( $logradouro )
  {
    if ( $logradouro == '' )
      throw new \Exception('Logradouro é obrigatório');

    $this->_table->logradouro = $logradouro;
  }

  public function setComplemento ( $complemento )
  {
    if ( $complemento == '' ) {
      $this->_table->complemento = null;
    } else {
      $this->_table->complemento = $complemento;
    }
  }

  public function setBairro ( $bairro )
  {
    if ( $bairro == '' )
      throw new \Exception('Bairro é obrigatório');

    $this->_table->bairro = $bairro;
  }

  public function setCidade ( $cidade )
  {
    if ( $cidade == '' )
      throw new \Exception('Cidade é obrigatório');

    $this->_table->cidade = $cidade;
  }

  public function setEstado ( $estado )
  {
    if ( $estado == '' )
      throw new \Exception('Estado é obrigatório');

    $this->_table->estado = $estado;
  }

  public function setOndeConheceu ( $onde_conheceu )
  {
    if ( $onde_conheceu == '' )
      throw new \Exception('Onde conheceu é obrigatório');

    $this->_table->onde_conheceu = $onde_conheceu;
  }

  public function setEmail ( $email )
  {
    if ( !$email = Validate::email_comma_separated($email) )
      throw new \Exception('É necessário pelo menos 1 e-mail.');

    $this->_table->email = $email;
  }

  public function getById ( $id )
  {
    $SQL = '
    SELECT
      a.*,
      b.ativo, b.usuario
    FROM
      ' . $this->_table->getName() . ' a
    INNER JOIN
      login b
    ON
      b.id_fornecedor = a.id_fornecedor
    {$strWhere}
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, array('a.id_fornecedor' => $id));

    if ( !count($result) )
      throw new \Exception('Fornecedor não encontrado.');

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $SQL = '
    SELECT
      a.id_fornecedor, a.rz_social, a.nome_contato,
      b.ativo
    FROM
      ' . $this->_table->getName() . ' a
    INNER JOIN
      login b
    ON
      b.id_fornecedor = a.id_fornecedor
    {$strWhere}
    ORDER BY
      a.rz_social
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $ativo, $rz_social, $nome_contato, $cnpj, $logradouro, $complemento, $bairro, $cidade, $estado, $email, $usuario, $senha, $servico, $produto, $marca, $onde_conheceu )
  {
    $this->_table->clear();

    $this->setRzSocial($rz_social);
    $this->setNomeContato($nome_contato);
    $this->setCnpj($cnpj);
    $this->setLogradouro($logradouro);
    $this->setComplemento($complemento);
    $this->setBairro($bairro);
    $this->setCidade($cidade);
    $this->setEstado($estado);
    $this->setOndeConheceu($onde_conheceu);
    $this->setEmail($email);
    $this->setIncData();

    $login = new LoginModel();
    $login->validate_insert($ativo, $usuario, $senha, 'F');

    $id_fornecedor = $this->_dao->insert($this->_table);

    $login->setIdFornecedor($id_fornecedor);

    $login->inserir();

    $this->saveListBox(new \src\tables\RFornecedorServicoTable, $id_fornecedor, $servico);
    $this->saveListBox(new \src\tables\RFornecedorProdutoTable, $id_fornecedor, $produto);
    $this->saveListBox(new \src\tables\RFornecedorMarcaTable, $id_fornecedor, $marca);

    return $id_fornecedor;
  }

  public function atualizar ( $id, $ativo, $rz_social, $nome_contato, $cnpj, $logradouro, $complemento, $bairro, $cidade, $estado, $email, $usuario, $senha, $servico, $produto, $marca, $onde_conheceu )
  {
    $fornecedor = $this->getById($id);

    $this->_table->clear();

    $this->setRzSocial($rz_social);
    $this->setNomeContato($nome_contato);
    $this->setCnpj($cnpj);
    $this->setLogradouro($logradouro);
    $this->setComplemento($complemento);
    $this->setBairro($bairro);
    $this->setCidade($cidade);
    $this->setEstado($estado);
    $this->setOndeConheceu($onde_conheceu);
    $this->setEmail($email);

    $login = new LoginModel();
    $id_login = $login->getIdLoginByIdFornecedor($id);
    $login->validate_update($id_login, $ativo, $usuario, $senha, 'F');
    $login->atualizar($id_login);

    $this->_dao->update($this->_table, $id);

    $this->saveListBox(new \src\tables\RFornecedorServicoTable, $id, $servico);
    $this->saveListBox(new \src\tables\RFornecedorProdutoTable, $id, $produto);
    $this->saveListBox(new \src\tables\RFornecedorMarcaTable, $id, $marca);

    return $id;
  }

}

