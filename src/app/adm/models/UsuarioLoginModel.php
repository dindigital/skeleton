<?php

namespace src\app\adm\models;

use src\tables\UsuarioTable;
use Din\Crypt\Crypt;
use Din\Cookie\Cookie;
use Din\Session\Session;
use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use Din\Auth\Auth;
use Din\Auth\AuthDataLayer\AuthDataLayer;
use Din\DataAccessLayer\Table\iTable;

/**
 *
 * @package app.models
 */
class UsuarioLoginModel
{

  public function __construct ()
  {
    $session_name = 'adm_sess';
    $cookie_name = 'adm_cook';

    $tbl = 'usuario';
    $pk_field = 'id_usuario';
    $login_field = '';
    $f_pass = $Table->getPass();
    $f_actv = $Table->getActive();

    $PDO = PDOBuilder::build();
    $ADL = new AuthDataLayer($PDO, $tbl, $f_login, $f_pass, $f_pk, $f_actv);

    $this->_crypt = new Crypt();
    $this->_auth = new Auth($ADL, $this->_crypt, new Session($session_name));
    $this->_cookie = new Cookie($cookie_name);
    $this->_dao = new DAO($PDO);
    $this->_table = $Table;
  }

  public function login ( $email, $senha, $memorizar = null )
  {
    if ( !$this->_auth->login($email, $senha) ) {
      throw new \Exception("Dados inválidos. Usuário não encontrado.");
    }

    if ( !$this->_auth->is_active() ) {
      $this->_auth->logout();
      throw new \Exception("Sua conta ainda não foi ativada. Entre em contato com o Administrador.");
    }

    if ( !is_null($memorizar) && $memorizar == '1' )
      $this->_cookie->set($login);
    else
      $this->_cookie->clear();
  }

  public function getUser ()
  {
    $SQL = '
    SELECT
      a.*
    FROM
      usuario a
    {$strWhere}
    ';
    $r = $this->_dao->getByCriteria($this->_table, $SQL, $this->_auth->getId());

    return $r;
  }

}
