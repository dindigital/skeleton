<?

namespace src\app\adm005\controllers;

/**
 *
 * @package app.controllers
 */
class Erro404Controller
{

  public function get_display ()
  {
    die('Ops. A página que você está tentando acessar não existe.');
  }

}

