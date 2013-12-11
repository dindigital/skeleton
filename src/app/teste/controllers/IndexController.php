<?php

namespace src\app\teste\controllers;

use src\app\teste\controllers\BaseControllerApp;
use src\app\teste\models\AgendaModel;

class IndexController extends BaseControllerApp
{

  public function get_index ()
  {
    $this->_data['url'] = URL;
    $this->set_agenda();

    $this->_view->addFile('src/app/teste/views/index.phtml', '{$MAIN}');
    $this->display_html();
  }

  private function set_agenda ()
  {
    $agenda = new AgendaModel();
    $result = $agenda->getAll();
    var_dump(count($result));
    $this->_data['noticias'] = 'Lorem Ipsum';
  }

}
