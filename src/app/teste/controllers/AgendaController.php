<?php

namespace src\app\teste\controllers;

use src\app\teste\controllers\BaseControllerApp;
use src\app\teste\models\AgendaModel;

class AgendaController extends BaseControllerApp
{

  public function get_inserts ()
  {
    $agenda = new AgendaModel();
    var_dump($agenda->inserts());
  }

  public function get_update ()
  {
    $agenda = new AgendaModel();
    var_dump($agenda->update());
  }

  public function get_delete ()
  {
    $agenda = new AgendaModel();
    var_dump($agenda->delete());
  }

  public function get_select ()
  {
    $agenda = new AgendaModel();
    $result = $agenda->getAll();

    $this->_data['agenda'] = $result;

    $this->_view->addFile('src/app/teste/views/agenda.phtml', '{$MAIN}');
    $this->display_html();
  }

}
