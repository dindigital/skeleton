<?php

namespace src\app\admin\controllers;

/**
 *
 * @package app.controllers
 */
class ExemploController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
  }

  public function get_lista ()
  {
    $this->_view->addFile('src/app/admin/views/exemplo_lista.phtml', '{$CONTENT}');
    $this->display_html();
  }

  public function get_cadastro ()
  {
    $this->setCadastroTemplate('exemplo_cadastro.phtml');
  }

  public function get_galeria ()
  {
    $this->setCadastroTemplate('exemplo_galeria.phtml');
  }

}
