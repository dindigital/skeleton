<?php

namespace src\app\admin\controllers;

use src\app\admin\controllers\essential\BaseControllerAdm;

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

  public function get_404 ()
  {
    $this->_view->removeFiles();
    $this->_view->addFile('src/app/admin/views/404.phtml');
    $this->display_html();
  }

  public function get_500 ()
  {
    $this->_view->removeFiles();
    $this->_view->addFile('src/app/admin/views/500.phtml');
    $this->display_html();
  }

  public function get_institucional ()
  {
    $this->_view->addFile('src/app/admin/views/institucional.phtml', '{$CONTENT}');
    $this->display_html();
  }

}
