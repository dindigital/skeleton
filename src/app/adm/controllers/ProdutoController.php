<?

namespace src\app\adm005\controllers;

use src\app\adm005\BaseControllerApp;
use src\app\adm005\models\ProdutoModel;
use src\app\adm005\objects\PaginatorPainel;
use src\app\adm005\controllers\LogController;
use lib\Form\Post\Post;

/**
 *
 * @package app.controllers
 */
class ProdutoController extends BaseControllerApp
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      parent::__construct($app_name, $Compressor);

      $this->_model = new ProdutoModel();
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_lista ()
  {
    try {

      $arrCriteria = array();

      $this->busca->nome = '';

      if ( isset($_GET['nome']) && $_GET['nome'] != '' ) {
        $arrCriteria['nome'] = array('LIKE' => '%' . $_GET['nome'] . '%');
        $this->busca->nome = $_GET['nome'];
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

      $this->registro_salvo($salvo);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_cadastro ( $id = null )
  {
    try {

      $nome = $_POST['nome'];
      $ativo = Post::checkbox('ativo');


      $content = array();

      if ( !$id ) {
        $id = $this->_model->inserir($nome, $ativo);

        $content['insert'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'C');
      } else {
        $content['before'] = $this->_model->getById($id);

        $this->_model->atualizar($id, $nome, $ativo);

        $content['after'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'U');
      }

      $this->alljax_redirect_after_save($id);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

