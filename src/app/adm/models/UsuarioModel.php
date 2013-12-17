<?php

namespace src\app\adm\models;

use Din\Paginator\Paginator;
use Din\DataAccessLayer\Select;
use src\app\adm\validators\UsuarioValidator;
use src\tables\UsuarioTable;
use \Exception;

/**
 *
 * @package app.models
 */
class UsuarioModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
  }

  public function inserir ( $info )
  {
    $usuario = new UsuarioValidator();
    $usuario->setAtivo($info['ativo']);
    $usuario->setNome($info['nome']);
    $usuario->setEmail($info['email']);
    $usuario->setSenha($info['senha']);
    $usuario->setIncData();
    $id = $usuario->setIdUsuario()->getTable()->id_usuario;

    $usuario->setArquivo('avatar', $info['avatar'], $id, false);

    $this->_dao->insert($usuario->getTable());

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $usuario = new UsuarioValidator();
    $usuario->setAtivo($info['ativo']);
    $usuario->setNome($info['nome']);
    $usuario->setEmail($info['email'], $id);
    $usuario->setSenha($info['senha'], false);

    $usuario->setArquivo('avatar', $info['avatar'], $id, false);

    return $this->_dao->update($usuario->getTable(), array('id_usuario' => $id));
  }

  public function salvar_config ( $id, $info )
  {
    $usuario = new UsuarioValidator();
    $usuario->setNome($info['nome']);
    $usuario->setEmail($info['email'], $id);
    $usuario->setSenha($info['senha'], false);
    $usuario->setArquivo('avatar', $info['avatar'], $id, false);

    return $this->_dao->update($usuario->getTable(), array('id_usuario' => $id));
  }

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $arrCriteria = array(
        'nome LIKE ?' => '%' . $arrFilters['nome'] . '%',
        'email LIKE ?' => '%' . $arrFilters['email'] . '%',
        'email <> ?' => 'suporte@dindigital.com'
    );

    $select = new Select('usuario');
    $select->addField('id_usuario');
    $select->addField('ativo');
    $select->addField('nome');
    $select->addField('email');
    $select->addField('inc_data');
    $select->where($arrCriteria);
    $select->order_by('nome');

    $this->setPaginationSelect($select, $paginator);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_usuario = ?' => $id
    );

    $select = new Select('usuario');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Usuário não encontrado.');

    return $result[0];
  }

  public function excluir ( $id )
  {
    $this->_dao->delete('usuario', array('id_usuario' => $id));
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $validator = new UsuarioValidator();
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array('id_usuario' => $id));
  }

}
