<?

namespace src\app\adm\controllers;

use src\app\adm\BaseControllerAdm;
use src\app\adm\models\ConfiguracaoModel;
use lib\Form\Post\Post;

/**
 *
 * @package app.controllers
 */
class ConfiguracaoController extends BaseControllerAdm
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      parent::__construct($app_name, $Compressor);

      $this->_model = new ConfiguracaoModel();
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_cadastro ( $salvo = false )
  {

    try {

      $this->action = $this->uri->cadastro;

      $this->table = $this->_model->getById('1');

      $this->registro_salvo($salvo);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_cadastro ()
  {
    try {

      $title_home = $_POST['title_home'];
      $description_home = $_POST['description_home'];
      $keywords_home = $_POST['keywords_home'];
      $title_interna = $_POST['title_interna'];
      $description_interna = $_POST['description_interna'];
      $keywords_interna = $_POST['keywords_interna'];
      $qtd_horas = $_POST['qtd_horas'];
      $email_avisos = $_POST['email_avisos'];

      $content = array();

      $content['before'] = $this->_model->getById();

      $this->_model->atualizar($title_home, $description_home, $keywords_home, $title_interna, $description_interna, $keywords_interna, $qtd_horas, $email_avisos);

      $content['after'] = $this->_model->getById();
      LogController::inserir($this, '1', $content, 'U');

      $this->alljax_redirect_after_save();
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

