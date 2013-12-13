<?

namespace src\app\adm005\controllers;

use src\app\adm005\BaseControllerApp;
use src\app\adm005\models\FornecedorModel;
use src\app\adm005\objects\PaginatorPainel;
use src\app\adm005\controllers\LogController;
use lib\Form\Post\Post;

/**
 *
 * @package app.controllers
 */
class FornecedorController extends BaseControllerApp
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      parent::__construct($app_name, $Compressor);

      $this->_model = new FornecedorModel();
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

      $produto = new \src\app\adm005\models\ProdutoModel();
      $this->table->produtos = $produto->getListbox($this->_model->loadListBox(new \src\tables\RFornecedorProdutoTable, $id));

      $servico = new \src\app\adm005\models\ServicoModel();
      $this->table->servicos = $servico->getListbox($this->_model->loadListBox(new \src\tables\RFornecedorServicoTable, $id));

      $marca = new \src\app\adm005\models\MarcaModel();
      $this->table->marcas = $marca->getListbox($this->_model->loadListBox(new \src\tables\RFornecedorMarcaTable, $id));

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
      $nome_contato = $_POST['nome_contato'];
      $cnpj = $_POST['cnpj'];
      $logradouro = $_POST['logradouro'];
      $complemento = $_POST['complemento'];
      $bairro = $_POST['bairro'];
      $cidade = $_POST['cidade'];
      $estado = $_POST['estado'];
      $email = $_POST['email'];
      $usuario = $_POST['usuario'];
      $senha = $_POST['senha'];
      $servico = Post::aray('servico');
      $produto = Post::aray('produto');
      $marca = Post::aray('marca');
      $onde_conheceu = $_POST['onde_conheceu'];

      $content = array();

      if ( !$id ) {
        $id = $this->_model->inserir($ativo, $rz_social, $nome_contato, $cnpj, $logradouro, $complemento, $bairro, $cidade, $estado, $email, $usuario, $senha, $servico, $produto, $marca, $onde_conheceu);

        $content['insert'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'C');
      } else {
        $content['before'] = $this->_model->getById($id);

        $this->_model->atualizar($id, $ativo, $rz_social, $nome_contato, $cnpj, $logradouro, $complemento, $bairro, $cidade, $estado, $email, $usuario, $senha, $servico, $produto, $marca, $onde_conheceu);

        $content['after'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'U');
      }

      $this->alljax_redirect_after_save($id);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

