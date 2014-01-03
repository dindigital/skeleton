<?php

namespace src\app\admin\controllers;

use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class Erro404Controller
{

  public function get_display ()
  {
    Header::set404();
    die('
      <h1>Erro 404</h1>
      <h2>Costomize este erro em src/app/admin/controllers/Erro404Controller</h2>


      ');
  }

}
