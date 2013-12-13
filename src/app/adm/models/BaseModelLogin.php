<?php

namespace src\app\adm\models;

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
 * @package MVC.Model
 */
class BaseModelLogin
{

  protected $_auth;
  protected $_crypt;
  protected $_cookie;
  protected $_table;
  protected $_dao;

  /**
   * Constrói objeto controlador do login
   *
   * @param iCrypt $Crypt
   * @param Auth $Auth
   * @param iCookieLayer $Cookie
   * @param DAO $DAO
   * @param iTable $Table
   */
  public function __construct ( $session_name, $cookie_name, iTable $Table )
  {

    $tbl = $Table->getName();
    $f_pk = $Table->getPk(true);
    $f_login = $Table->getLogin();
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

  /**
   * Retorna o objeto do tipo Crypt que foi iniciado pelo construtor.
   * @return iCrypt
   */
  public function getCryptObject ()
  {
    return $this->_crypt;
  }

  /**
   * Verifica se o usuário está logado.
   * @return bool
   */
  public function is_logged ()
  {
    return $this->_auth->is_logged();
  }

  /**
   * Retorna o nome de usuário armazenado em cookie
   * @return string
   */
  public function getCookie ()
  {
    return $this->_cookie->is_set() ? $this->_cookie->get() : '';
  }

  /**
   * Realiza o logout
   */
  public function logout ()
  {
    $this->_auth->logout();
  }

}

// END








