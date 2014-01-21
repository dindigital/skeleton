<?php

namespace src\app\admin\controllers;

use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\ConfiguracaoModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\viewhelpers\ConfiguracaoViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class ConfiguracaoController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model();
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_cadastro ()
  {
    $this->_data['table'] = vh::formatRow($this->_model->getById('1'));
    $this->setCadastroTemplate('configuracao_cadastro.phtml');
  }

  public function post_cadastro ()
  {
    try {

      $info = array(
          'title_home' => Post::text('title_home'),
          'description_home' => Post::text('description_home'),
          'keywords_home' => Post::text('keywords_home'),
          'title_interna' => Post::text('title_interna'),
          'description_interna' => Post::text('description_interna'),
          'keywords_interna' => Post::text('keywords_interna'),
          'qtd_horas' => Post::text('qtd_horas'),
          'email_avisos' => Post::text('email_avisos'),
      );

      $this->saveAndRedirect($info, '1');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}
