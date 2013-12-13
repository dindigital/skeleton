<?

namespace src\app\adm\controllers;

use src\app\adm\BaseControllerAdm;
use src\app\adm\models\UsuarioModel;
use src\app\adm\controllers\LogController;
use lib\Form\Post\Post;
use src\app\adm\objects\Upload;

/**
 *
 * @package app.controllers
 */
class ConfigController extends BaseControllerAdm
{

  public function __construct ( $app_name, $assets )
  {
    try {

      parent::__construct($app_name, $assets);

      $this->_model = new UsuarioModel();
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_cadastro ( $salvo = false )
  {
    try {

      $this->action = $this->uri->cadastro;

      $this->table = $this->_model->getById($this->user_table->id_usuario);

      Upload::set($this->table, 'avatar', 'imagem');

      $this->registro_salvo($salvo);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_cadastro ()
  {
    try {

      $id_usuario = $this->user_table->id_usuario;
      $nome = $_POST['nome'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];
      $avatar = Post::upload('avatar');

      $content = array();

      $content['before'] = $this->_model->getById($id_usuario);

      $this->_model->salvar_config($id_usuario, $nome, $email, $senha, $avatar);

      $content['after'] = $this->_model->getById($id_usuario);
      LogController::inserir($this, $id_usuario, $content, 'U');

      $this->alljax_redirect_after_save(null, false);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}
