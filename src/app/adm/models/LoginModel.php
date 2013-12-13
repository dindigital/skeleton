<?

namespace src\app\adm005\models;

use src\models\Login;
use lib\Validation\Validate;
use src\app\adm005\objects\Arrays;
use lib\DataAccessLayer\Select;

/**
 *
 * @package app.models
 */
class LoginModel extends Login
{

  public function __construct ()
  {
    parent::__construct();
    $this->setMe('Login');
  }

  public function setIdFornecedor ( $id_fornecedor )
  {
    if ( $id_fornecedor != '' ) {
      $select = new Select('fornecedor');
      $select->addFField('total', 'COUNT(*)');
      $select->where(array(
          'id_fornecedor' => $id_fornecedor
      ));

      $result = $this->_dao->select($select);

      if ( !count($result) )
        throw new \Exception('Fornecedor não encontrado.');

      $this->_table->id_fornecedor = $id_fornecedor;
    }
  }

  public function setIdCliente ( $id_cliente )
  {
    if ( $id_cliente != '' ) {
      $select = new Select('cliente');
      $select->addFField('total', 'COUNT(*)');
      $select->where(array(
          'id_cliente' => $id_cliente
      ));

      $result = $this->_dao->select($select);

      if ( !count($result) )
        throw new \Exception('Cliente não encontrado.');

      $this->_table->id_cliente = $id_cliente;
    }
  }

  public function verify_cliente_fornecedor ()
  {
    if ( is_null($this->_table->id_cliente) && is_null($this->_table->id_fornecedor) )
      throw new \Exception('Para ter login é necessário ser cliente ou fornecedor.');
  }

  public function setUsuario ( $usuario, $id = null, $tipo )
  {
    if ( $tipo == 'C' ) {
      if ( !Validate::email($usuario) )
        throw new \Exception('Usuario de cliente deve ser um e-mail válido.');
    }else {
      if ( !Validate::username($usuario) )
        throw new \Exception('Usuario é um campo alfanumérico começando com uma letra devendo ter no mínimo 6 dígitos');
    }

    $select = new Select('login');
    $select->addFField('total', 'COUNT(*)');
    $arrCriteria['usuario'] = $usuario;
    if ( $id ) {
      $arrCriteria['id_login'] = array('<>' => $id);
    }
    $select->where($arrCriteria);
    $result = $this->_dao->select($select);



    $total = intval($result[0]->total);

    if ( $total )
      throw new \Exception('Nome de usuário já está em uso, por favor escolha outro');

    $this->_table->usuario = $usuario;
  }

  public function setSenha ( $senha, $obg = true )
  {
    if ( $senha == '' && $obg )
      throw new \Exception('Senha é obrigatório');

    if ( $senha != '' ) {
      $crypt = new \lib\Crypt\Crypt();
      $this->_table->senha = $crypt->crypt($senha);
    }
  }

  public function setTipo ( $tipo )
  {
    if ( !Validate::array_key(Arrays::arrayTipoLogin(), $tipo) )
      throw new \Exception('Tipo é obrigatório');

    $this->_table->tipo = $tipo;
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
      throw new \Exception('Login não encontrado.');

    return $result[0];
  }

  public function getIdLoginByIdCliente ( $id_cliente )
  {
    $select = new Select('login');
    $select->addField('id_login');
    $select->where(array(
        'id_cliente' => $id_cliente
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new \Exception('Login não encontrado.');

    return $result[0]->id_login;
  }

  public function getIdLoginByIdFornecedor ( $id_fornecedor )
  {
    $select = new Select('login');
    $select->addField('id_login');
    $select->where(array(
        'id_fornecedor' => $id_fornecedor
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new \Exception('Login não encontrado.');

    return $result[0]->id_login;
  }

  public function validate_insert ( $ativo, $usuario, $senha, $tipo )
  {
    $this->_table->clear();

    $this->setAtivo($ativo);
    $this->setUsuario($usuario, null, $tipo);
    $this->setSenha($senha);
    $this->setTipo($tipo);
  }

  public function inserir ()
  {
    $this->verify_cliente_fornecedor();
    $id = $this->_dao->insert($this->_table);

    return $id;
  }

  public function validate_update ( $id, $ativo, $usuario, $senha, $tipo )
  {
    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Registro não encontrado.');

    $this->setAtivo($ativo);
    $this->setUsuario($usuario, $id, $tipo);
    $this->setSenha($senha, false);
    $this->setTipo($tipo);
  }

  public function atualizar ( $id )
  {
    $this->_dao->update($this->_table, $id);

    return $id;
  }

}

