<?php

namespace src\app\adm\models;

use Din\Paginator\Paginator;
use Din\DataAccessLayer\Select;

/**
 *
 * @package app.models
 */
class UsuarioModel extends BaseModelAdm
{

  public function setNome ( $nome )
  {
    if ( $nome == '' )
      throw new \Exception('Nome é obrigatório');

    $this->_table->nome = $nome;
  }

  public function setEmail ( $email, $id = null )
  {
    if ( $email == '' || !\lib\Validation\Validate::email($email) )
      throw new \Exception('E-mail usuário deve ser um e-mail válido');

    $SQL = "SELECT * FROM usuario {\$strWhere}";
    $arrCriteria = array();
    $arrCriteria['email'] = $email;
    if ( $id ) {
      $arrCriteria['id_usuario'] = array('<>' => $id);
    }

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrCriteria);

    if ( count($result) )
      throw new \Exception('Este e-mail já existe.');

    $this->_table->email = $email;
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

  public function inserir ( $ativo, $nome, $email, $senha, $avatar )
  {
    $this->_table->clear();

    $this->setAtivo($ativo);
    $this->setNome($nome);
    $this->setEmail($email);
    $this->setSenha($senha);
    $this->setIncData();

    $id = $this->_dao->insert($this->_table);

    $this->setArquivo('avatar', $avatar, $id, false);
    $this->_dao->update($this->_table, $id);

    return $id;
  }

  public function atualizar ( $id, $ativo, $nome, $email, $senha, $avatar )
  {
    $this->_table->clear();

    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Usuário não encontrado.');

    $this->setAtivo($ativo);
    $this->setNome($nome);
    $this->setEmail($email, $id);
    $this->setSenha($senha, false);

    $this->setArquivo('avatar', $avatar, $id, false);

    return $this->_dao->update($this->_table, $id);
  }

  public function salvar_config ( $id, $nome, $email, $senha, $avatar )
  {
    $this->_table->clear();

    if ( !$this->_dao->countByPk($this->_table, $id) )
      throw new \Exception('Usuário não encontrado.');

    $this->setNome($nome);
    $this->setEmail($email, $id);
    $this->setSenha($senha, false);
    $this->setArquivo('avatar', $avatar, $id, false);

    return $this->_dao->update($this->_table, $id);
  }

  public function listar ( $arrParams = array(), Paginator $Paginator = null )
  {
    $arrParams['id_usuario']['<>'] = '1';

    $SQL = '
    SELECT
      u.id_usuario,u.nome,u.email,u.ativo,u.inc_data
    FROM
      usuario u
    {$strWhere}
    ORDER BY
      u.nome
    ';

    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrParams, $Paginator);

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
