<?php

namespace src\app\admin\models;

use Din\Paginator\Paginator;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\UsuarioValidator;
use \Exception;
use Din\Exception\JsonException;

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
    $validator = new UsuarioValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setNome($info['nome']);
    $validator->setEmail($info['email']);
    $validator->setSenha($info['senha']);
    $validator->setIncData();
    $id = $validator->setIdUsuario()->getTable()->id_usuario;

    $validator->setArquivo('avatar', $info['avatar'], $id, false);
    $validator->setArquivo('avatar2', $info['avatar2'], $id, false);
    $validator->setArquivo('avatar3', $info['avatar3'], $id, false);
    $validator->throwException();

    try {
      $this->_dao->insert($validator->getTable());
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }

    return $id;
  }

  public function atualizar ( $id, $info )
  {
    $validator = new UsuarioValidator();
    $validator->setAtivo($info['ativo']);
    $validator->setNome($info['nome']);
    $validator->setEmail($info['email'], $id);
    $validator->setSenha($info['senha'], false);

    $validator->setArquivo('avatar', $info['avatar'], $id, false);
    $validator->setArquivo('avatar2', $info['avatar2'], $id, false);
    $validator->setArquivo('avatar3', $info['avatar3'], $id, false);
    $validator->throwException();

    try {
      return $this->_dao->update($validator->getTable(), array('id_usuario = ?' => $id));
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }
  }

  public function salvar_config ( $id, $info )
  {
    $validator = new UsuarioValidator();
    $validator->setNome($info['nome']);
    $validator->setEmail($info['email'], $id);
    $validator->setSenha($info['senha'], false);
    $validator->setArquivo('avatar', $info['avatar'], $id, false);
    $validator->throwException();

    try {
      return $this->_dao->update($validator->getTable(), array('id_usuario = ?' => $id));
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }
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
    try {
      $this->_dao->delete('usuario', array('id_usuario = ?' => $id));
    } catch (Exception $e) {
      JsonException::addException($e->getMessage());
      JsonException::throwException();
    }
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $validator = new UsuarioValidator();
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array('id_usuario = ?' => $id));
  }

}
