<?

namespace src\app\adm\controllers;

use src\app\adm\BaseControllerAdm;
use src\app\adm\models\UsuarioModel;
use src\app\adm\objects\PaginatorPainel;
use src\app\adm\controllers\LogController;
use lib\Form\Post\Post;
use src\app\adm\objects\Upload;

/**
 *
 * @package app.controllers
 */
class UsuarioController extends BaseControllerAdm
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

  public function get_lista ()
  {
    try {

      $this->busca->nome = '';
      $this->busca->email = '';

      $arrCriteria = array();
      if ( isset($_GET['nome']) && $_GET['nome'] != '' ) {
        $arrCriteria['nome'] = array('LIKE' => '%' . $_GET['nome'] . '%');
        $this->busca->nome = $_GET['nome'];
      }

      if ( isset($_GET['email']) && $_GET['email'] != '' ) {
        $arrCriteria['email'] = array('LIKE' => '%' . $_GET['email'] . '%');
        $this->busca->email = $_GET['email'];
      }

      $this->paginator = new PaginatorPainel(20, 7, @$_GET['pag']);

      $this->list = $this->_model->listar($arrCriteria, $this->paginator);
      $this->action = $this->uri->lista;

      $this->alljax_view($this->uri->view_lista);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_cadastro ( $id = null, $salvo = false )
  {
    try {

      $this->action = $this->uri->cadastro;

      if ( $id ) {
        $this->action .= $id . '/';
        $this->table = $this->_model->getById($id);
      } else {
        $this->table = $this->_model->novo();
      }

      Upload::set($this->table, 'avatar', 'imagem');

      $this->registro_salvo($salvo);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_cadastro ( $id = null )
  {
    try {

      $ativo = Post::checkbox('ativo');
      $nome = $_POST['nome'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];
      $avatar = Post::upload('avatar');

      $content = array();

      if ( !$id ) {
        $id = $this->_model->inserir($ativo, $nome, $email, $senha, $avatar);

        $content['insert'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'C');
      } else {
        $content['before'] = $this->_model->getById($id);

        $this->_model->atualizar($id, $ativo, $nome, $email, $senha, $avatar);

        $content['after'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'U');
      }

      $this->alljax_redirect_after_save($id, false);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}
