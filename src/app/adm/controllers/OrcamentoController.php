<?

namespace src\app\adm005\controllers;

use src\app\adm005\BaseControllerApp;
use src\app\adm005\models\OrcamentoModel;
use src\app\adm005\objects\PaginatorPainel;
use src\app\adm005\controllers\LogController;
use lib\Form\Post\Post;
use src\app\adm005\objects\Dropdown;
use src\app\adm005\objects\Arrays;
use lib\Validation\DateTransform;
use lib\Validation\Validate;

/**
 *
 * @package app.controllers
 */
class OrcamentoController extends BaseControllerApp
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      parent::__construct($app_name, $Compressor);

      $this->_model = new OrcamentoModel;
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_lista ()
  {
    try {

      $arrCriteria = array();

      $this->busca->cliente = '';

      if ( isset($_GET['cliente']) && $_GET['cliente'] != '' ) {
        $arrCriteria['b.rz_social'] = array('LIKE' => '%' . $_GET['cliente'] . '%');
        $this->busca->cliente = $_GET['cliente'];
      }

      $this->busca->situacao = '';

      if ( isset($_GET['situacao']) && $_GET['situacao'] != '' && $_GET['situacao'] != '0' ) {
        $arrCriteria['a.situacao'] = $_GET['situacao'];
        $this->busca->situacao = $_GET['situacao'];
      }

      $this->busca->data1 = '';

      if ( isset($_GET['data1']) && Validate::data($_GET['data1']) ) {
        $arrCriteria['a.inc_data']['>='] = DateTransform::sql_date($_GET['data1']) . ' 00:00:00';
        $this->busca->data1 = $_GET['data1'];
      }

      $this->busca->data2 = '';

      if ( isset($_GET['data2']) && Validate::data($_GET['data2']) ) {
        $arrCriteria['a.inc_data']['<='] = DateTransform::sql_date($_GET['data2']) . ' 23:59:59';
        $this->busca->data2 = $_GET['data2'];
      }

      $this->paginator = new PaginatorPainel(20, 7, @$_GET['pag']);

      $this->list = $this->_model->listar($arrCriteria, $this->paginator);
      $this->action = $this->uri->lista;

      Dropdown::set($this->busca, 'situacao', Arrays::arraySituacaoPedido(), 'Filtro por Situação');


      $this->alljax_view($this->uri->view_lista);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_cadastro ( $id )
  {

    try {

      $this->table = $this->_model->getById($id);

      $this->alljax_view($this->uri->view_cadastro);
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
      $endereco = $_POST['endereco'];
      $email = $_POST['email'];
      $usuario = $_POST['usuario'];
      $senha = $_POST['senha'];
      $servico = Post::aray('servico');
      $produto = Post::aray('produto');
      $marca = Post::aray('marca');
      $onde_conheceu = $_POST['onde_conheceu'];

      $content = array();

      if ( !$id ) {
        $id = $this->_model->inserir($ativo, $rz_social, $nome_contato, $cnpj, $endereco, $email, $usuario, $senha, $servico, $produto, $marca, $onde_conheceu);

        $content['insert'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'C');
      } else {
        $content['before'] = $this->_model->getById($id);

        $this->_model->atualizar($id, $ativo, $rz_social, $nome_contato, $cnpj, $endereco, $email, $usuario, $senha, $servico, $produto, $marca, $onde_conheceu);

        $content['after'] = $this->_model->getById($id);
        LogController::inserir($this, $id, $content, 'U');
      }

      $this->alljax_redirect_after_save($id);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_excluir ()
  {
    try {

      $itens = Post::aray('itens');

      $list = array();
      foreach ( $itens as $i => $item ) {
        list($tbl, $id) = explode('_', $item);
        $itens[$i] = array('tbl' => $tbl, 'id' => $id);

        $model_name = "\\src\\app\\adm005\\models\\OrcamentoModel";
        $model = new $model_name;

        $responsavel = $this->user_table->nome;
        $secao = $model->getMe(true);
        $nome_legivel = $model->getMe();
        $tbl_instantce = $model->getById($id);
        $descricao = $tbl_instantce->{$tbl_instantce->getTitle()};

        LogController::inserir_manual($responsavel, $secao, $nome_legivel, 'D', $descricao);
      }

      foreach ( $itens as $item ) {
        $id = $item['id'];
        $this->_model->excluir($id);
      }

      $this->alljax_redirect($_SERVER['HTTP_REFERER']);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

