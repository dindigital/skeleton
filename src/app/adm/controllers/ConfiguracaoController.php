<?php

namespace src\app\adm\controllers;

use src\app\adm\controllers\BaseControllerAdm;
use src\app\adm\models\ConfiguracaoModel;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class ConfiguracaoController extends BaseControllerAdm
{

  private $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new ConfiguracaoModel();
  }

  public function get_cadastro ()
  {
    $this->_data['table'] = $this->_model->getById(1);
    $this->setCadastroTemplate('configuracao_cadastro.php');
  }

  public function post_cadastro ()
  {
    try {
      $this->_model->atualizar('1', array(
          'title_home' => Post::text('title_home'),
          'description_home' => Post::text('description_home'),
          'keywords_home' => Post::text('keywords_home'),
          'title_interna' => Post::text('title_interna'),
          'description_interna' => Post::text('description_interna'),
          'keywords_interna' => Post::text('keywords_interna'),
          'qtd_horas' => Post::text('qtd_horas'),
          'email_avisos' => Post::text('email_avisos'),
      ));

      $this->setRegistroSalvoSession();

      JsonViewHelper::redirect('/adm/configuracao/cadastro/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
