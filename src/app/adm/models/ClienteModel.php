<?

namespace src\app\adm005\models;

use src\models\Cliente;
use lib\Paginator\Paginator;
use lib\Validation\Validate;
use src\app\adm005\objects\Arrays;

/**
 *
 * @package app.models
 */
class ClienteModel extends Cliente
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Cliente');
  }

  public function setRzSocial ( $rz_social )
  {
    if ( $rz_social == '' )
      throw new \Exception('Razão Social é obrigatório');

    $this->_table->rz_social = $rz_social;
  }

  public function setCnpj ( $cnpj )
  {
    if ( $cnpj == '' )
      throw new \Exception('CNPJ é obrigatório');

    $this->_table->cnpj = $cnpj;
  }

  public function setNomeContato ( $nome_contato )
  {
    if ( $nome_contato == '' )
      throw new \Exception('Nome Contato é obrigatório');

    $this->_table->nome_contato = $nome_contato;
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

  public function setQtdFuncionario ( $qtd_funcionario )
  {
    if ( !Validate::array_key(Arrays::arrayQtdFuncionario(), $qtd_funcionario) )
      throw new \Exception('Qtd. Funcionários é obrigatório');

    $this->_table->qtd_funcionario = $qtd_funcionario;
  }

  public function setEmail ( $email )
  {
    if ( !Validate::email($email) )
      throw new \Exception('Email deve conter um e-mail válido');

    $this->_table->email = $email;
  }

  public function getById ( $id )
  {
    $SQL = '
    SELECT
      a.*,
      b.ativo
    FROM
      ' . $this->_table->getName() . ' a
    INNER JOIN
      login b
    ON
      b.id_cliente = a.id_cliente
    {$strWhere}
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, array('a.id_cliente' => $id));

    if ( !count($result) )
      throw new \Exception('Cliente não encontrado.');

    return $result[0];
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $SQL = '
    SELECT
      a.id_cliente, a.rz_social, a.nome_contato,
      b.ativo
    FROM
      ' . $this->_table->getName() . ' a
    INNER JOIN
      login b
    ON
      b.id_cliente = a.id_cliente
    {$strWhere}
    ORDER BY
      a.rz_social
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

    return $result;
  }

  public function inserir ( $ativo, $rz_social, $cnpj, $nome_contato, $logradouro, $complemento, $bairro, $cidade, $estado, $onde_conheceu, $qtd_funcionario, $email, $senha )
  {
    $this->_table->clear();

    $this->setRzSocial($rz_social);
    $this->setCnpj($cnpj);
    $this->setNomeContato($nome_contato);
    $this->setLogradouro($logradouro);
    $this->setComplemento($complemento);
    $this->setBairro($bairro);
    $this->setCidade($cidade);
    $this->setEstado($estado);
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

  public function atualizar ( $id, $ativo, $rz_social, $cnpj, $nome_contato, $logradouro, $complemento, $bairro, $cidade, $estado, $onde_conheceu, $qtd_funcionario, $email, $senha )
  {
    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Registro não encontrado.');

    $this->setRzSocial($rz_social);
    $this->setCnpj($cnpj);
    $this->setNomeContato($nome_contato);
    $this->setLogradouro($logradouro);
    $this->setComplemento($complemento);
    $this->setBairro($bairro);
    $this->setCidade($cidade);
    $this->setEstado($estado);
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

