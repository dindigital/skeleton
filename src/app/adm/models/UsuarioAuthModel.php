<?php

namespace src\app\adm\models;

use Din\Crypt\Crypt;
use Din\Session\Session;
use Din\DataAccessLayer\PDO\PDOBuilder;
use Din\Auth\Auth;
use Din\Auth\AuthDataLayer\AuthDataLayer;

/**
 *
 * @package app.models
 */
class UsuarioAuthModel extends Auth
{

  public function __construct ()
  {
    $session_name = 'adm_session';
    $tbl = 'usuario';
    $pk_field = 'id_usuario';
    $login_field = 'email';
    $pass_field = 'senha';
    $active_field = 'ativo';

    $PDO = PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS);
    $ADL = new AuthDataLayer($PDO, $tbl, $login_field, $pass_field, $pk_field, $active_field);

    parent::__construct($ADL, new Crypt(), new Session($session_name));
  }

  public function login ( $email, $senha, $is_crypted = false )
  {
    if ( !parent::login($email, $senha) ) {
      throw new \Exception("Dados inválidos. Usuário não encontrado.");
    }

    if ( !$this->is_active() ) {
      $this->logout();
      throw new \Exception("Sua conta ainda não foi ativada. Entre em contato com o Administrador.");
    }
  }

}
