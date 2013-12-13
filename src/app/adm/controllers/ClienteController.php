<?

namespace src\app\adm005\controllers;

use src\app\adm005\BaseControllerApp;
use src\app\adm005\models\ClienteModel;
use src\app\adm005\objects\PaginatorPainel;
use src\app\adm005\controllers\LogController;
use lib\Form\Post\Post;
use src\app\adm005\objects\Dropdown;
use src\app\adm005\objects\Arrays;

/**
 *
 * @package app.controllers
 */
class ClienteController extends BaseControllerApp
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      parent::__construct($app_name, $Compressor);

      $this->_model = new ClienteModel();
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_lista ()
  {
    try {

      $arrCriteria = array();

      $this->busca->rz_social = '';

      if ( isset($_GET['rz_social']) && $_GET['rz_social'] != '' ) {
        $arrCriteria['rz_social'] = array('LIKE' => '%' . $_GET['rz_social'] . '%');
        $this->busca->rz_social = $_GET['rz_social'];
      }

      $this->busca->nome_contato = '';

      if ( isset($_GET['nome_contato']) && $_GET['nome_contato'] != '' ) {
        $arrCriteria['nome_contato'] = array('LIKE' => '%' . $_GET['nome_contato'] . '%');
        $this->busca->nome_contato = $_GET['nome_contato'];
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

      Dropdown::set($this->table, 'qtd_funcionario', Arrays::arrayQtdFuncionario(), '', $id);

      $this->registro_salvo($salvo);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_cadastro ( $id = null )
  {
    try {

      $ativo = Post::checkbox('ativo');
      $rz_social = $_POST['rz_social'];
      $cnpj = $_POST['cnpj'];
      $nome_contato = $_POST['nome_contato'];
      $logradouro = $_POST['logradouro'];
      $complemento = $_POST['complemento'];
      $bairro = $_POST['bairro'];
      $cidade = $_POST['cidade'];
      $estado = $_POST['estado'];
      $onde_conheceu = $_POST['onde_conheceu'];
      $qtd_funcionario = $_POST['qtd_funcionario'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];


      $content = array();

      if ( !$id ) {
        $id = $this->_model->inserir($ativo, $rz_social, $cnpj, $nome_contato, $logradouro, $complemento, $bairro, $cidade, $estado, $onde_conheceu, $qtd_funcionario, $email, $senha);

        $content['insert'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'C');
      } else {
        $content['before'] = $this->_model->getById($id);

        $this->_model->atualizar($id, $ativo, $rz_social, $cnpj, $nome_contato, $logradouro, $complemento, $bairro, $cidade, $estado, $onde_conheceu, $qtd_funcionario, $email, $senha);

        $content['after'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'U');
      }

      $this->alljax_redirect_after_save($id);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_ativo ()
  {
    $id = $_POST['id'];
    $ativo = $_POST['ativo'];
    $content = array();

    $content['before'] = $this->_model->getById($id);

    $login = new \src\app\adm005\models\LoginModel();
    $login->toggleAtivo($login->getIdLoginByIdCliente($id)->ativo, $ativo);
    $this->_model->toggleAtivo($id, $ativo);

    $content['after'] = $this->_model->getById($id);
    $this->log($id, $content, 'U');
  }

}

