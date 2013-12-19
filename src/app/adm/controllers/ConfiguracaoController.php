<?php

namespace src\app\adm\controllers;

use src\app\adm\controllers\BaseControllerAdm;
use src\app\adm\models\ConfiguracaoModel;
use Din\Http\Post;
use Din\Http\Header;

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

    /* $errorDetail = array();
      $errorDetail[] = array(
      'field' => 'nome',
      'msg' => 'Nome é um campo obrgatório'
      );
      $errorDetail[] = array(
      'field' => 'email',
      'msg' => 'E-mail existe em nossa base de dados'
      );
      $errorDetail[] = array(
      'field' => 'cpf',
      'msg' => 'CPF Inválido'
      );

      $teste = array(
      'type' => 'error',
      'errorDetail' => $errorDetail
      ); */

    /* $teste = array(
      'type' => 'redirect',
      'uri' => '/adm'
      ); */

    $teste = array(
        'type' => 'success',
        'message' => 'Cadastro OK!'
    );
    echo json_encode($teste);
    exit;

    $this->setRegistroSalvoSession();
    Header::redirect();
  }

}

