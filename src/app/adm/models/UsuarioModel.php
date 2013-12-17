<?php

namespace src\app\adm\models;

use Din\Paginator\Paginator;
use Din\DataAccessLayer\Select;
use src\tables\UsuarioTable;
use Din\Validation\Validate;
use Din\Crypt\Crypt;

/**
 *
 * @package app.models
 */
class UsuarioModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->_table = new UsuarioTable();
  }

  public function setNome ( $nome )
  {
    if ( $nome == '' )
      throw new \Exception('Nome é obrigatório');

    $this->_table->nome = $nome;
  }

  public function setEmail ( $email, $id = null )
  {
    if ( $email == '' || !Validate::email($email) )
      throw new \Exception('E-mail usuário deve ser um e-mail válido');

    $SQL = "SELECT * FROM usuario {\$strWhere}";
    $arrCriteria = array();
    $arrCriteria['email'] = $email;
    if ( $id ) {
      $arrCriteria['id_usuario'] = array('<>' => $id);
    }

//    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrCriteria);
//
//    if ( count($result) )
//      throw new \Exception('Este e-mail já existe.');

    $this->_table->email = $email;
  }

  public function setSenha ( $senha, $obg = true )
  {
    if ( $senha == '' && $obg )
      throw new \Exception('Senha é obrigatório');

    if ( $senha != '' ) {
      $crypt = new Crypt();
      $this->_table->senha = $crypt->crypt($senha);
    }
  }

  public function inserir ( $info )
  {
    $this->setAtivo($info['ativo']);
    $this->setNome($info['nome']);
    $this->setEmail($info['email']);
    $this->setSenha($info['senha']);
    $this->setIncData();

    $id = $this->_dao->insert($this->_table);

    $this->setArquivo('avatar', $info['senha'], $id, false);
    $this->_dao->update($this->_table, $id);

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $this->setAtivo($info['ativo']);
    $this->setNome($info['nome']);
    $this->setEmail($info['email'], $id);
    $this->setSenha($info['senha'], false);

    $this->setArquivo('avatar', $info['avatar'], $id, false);

    return $this->_dao->update($this->_table, array('id_usuario' => $id));
  }

  public function salvar_config ( $id, $info )
  {
    $this->setNome($info['nome']);
    $this->setEmail($info['email'], $id);
    $this->setSenha($info['senha'], false);
    $this->setArquivo('avatar', $info['avatar'], $id, false);

    return $this->_dao->update($this->_table, array('id_usuario' => $id));
  }

  public function listar ( $arrFilters = array(), Paginator $Paginator = null )
  {
    $arrCriteria = array(
        'nome' => array('LIKE' => '%' . $arrFilters['nome'] . '%'),
        'email' => array('LIKE' => '%' . $arrFilters['email'] . '%'),
        'id_usuario' => array('<>' => '1')
    );

    $select = new Select('usuario');
    $select->addField('*');
    $select->where($arrCriteria);
    $select->order_by('nome');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_usuario' => $id
    );

    $select = new Select('usuario');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new \Exception('Usuário não encontrado.');

    return $result[0];
  }

  public function getDropdown ( $firstOption = null, $selected = null )
  {
    $d = new \lib\Form\Dropdown\Dropdown('usuario');

    $SQL = "SELECT id_usuario, nome FROM usuario {\$strWhere} ORDER BY nome";
    $arrayObj = $this->_dao->getByCriteria($this->_table, $SQL, array(
    ));
    $d->setOptionsObj($arrayObj, 'id_usuario', 'nome');
    $d->setClass('uniform');
    $d->setSelected($selected);

    if ( $firstOption ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

}
