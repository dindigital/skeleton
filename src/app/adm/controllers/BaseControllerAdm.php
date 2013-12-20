<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\adm\models\UsuarioModel;
use src\app\adm\models\UsuarioAuthModel;
use \Exception;
use Din\Session\Session;
use Din\Image\Picuri;
use Din\Http\Post;
use Din\Http\Header;
use Din\Filters\Date\DateFormat;

/**
 * Classe abstrata que será a base de todos os controllers do adm
 */
abstract class BaseControllerAdm extends BaseController
{

  public function __construct ()
  {
    parent::__construct();

    $this->setAssetsData();
    $this->setUserData();
    $this->setBasicTemplate();
    $this->setViewHelpers();
    $this->setDefaultHeaders();
  }

  private function setDefaultHeaders ()
  {
    Header::setNoCache();
  }

  /**
   * Seta os arquivos que compõem o layout do adm
   */
  private function setBasicTemplate ()
  {
    $this->_view->addFile('src/app/adm/views/layout.phtml');
    $this->_view->addFile('src/app/adm/views/includes/head.php', '{$HEAD}');
    $this->_view->addFile('src/app/adm/views/includes/side_bar.php', '{$SIDE_BAR}');
    $this->_view->addFile('src/app/adm/views/includes/footer.php', '{$FOOTER}');
  }

  public function setCadastroTemplate ( $filename )
  {
    $this->setRegistroSalvoData();

    $this->_view->addFile('src/app/adm/views/includes/cadastro_alerts.php', '{$CADASTRO_ALERTS}');
    $this->_view->addFile('src/app/adm/views/includes/cadastro_submit.php', '{$CADASTRO_SUBMIT}');
    $this->_view->addFile('src/app/adm/views/includes/btn_novo_registro.php', '{$BTN_NOVO_REGISTRO}');

    $this->_view->addFile('src/app/adm/views/' . $filename, '{$CONTENT}');
    $this->display_html();
  }

  public function setListTemplate ( $filename, $paginator )
  {
    $this->_data['paginator']['subtotal'] = $paginator->getSubTotal();
    $this->_data['paginator']['total'] = $paginator->getTotal();
    $this->_data['paginator']['numbers'] = $paginator->getNumbers();

    $this->_view->addFile('src/app/adm/views/includes/btn_limpar.php', '{$BTN_LIMPAR}');
    $this->_view->addFile('src/app/adm/views/includes/btn_buscar.php', '{$BTN_BUSCAR}');
    $this->_view->addFile('src/app/adm/views/includes/btn_excluir_permanentemente.php', '{$BTN_EXCLUIR_PERMANENTEMENTE}');
    $this->_view->addFile('src/app/adm/views/includes/btn_novo_registro.php', '{$BTN_NOVO_REGISTRO}');
    $this->_view->addFile('src/app/adm/views/includes/lista_footer.php', '{$LISTA_FOOTER}');

    $this->_view->addFile('src/app/adm/views/' . $filename, '{$CONTENT}');
    $this->display_html();
  }

  /**
   * Seta os assets
   */
  private function setAssetsData ()
  {
    $this->_data['assets'] = $this->getAssets();
  }

  public function setViewHelpers ()
  {
    $this->_data['DateFormat'] = new DateFormat;
  }

  /**
   * Verifica se usuário está logado e seta os dados de usuário
   * @throws Exception - Caso o usuário não esteja logado
   */
  private function setUserData ()
  {
    $usuarioAuthModel = new UsuarioAuthModel();
    if ( !$usuarioAuthModel->is_logged() )
      throw new Exception('Permissão negada, usuário deve estar logado.');

    $usuario = new UsuarioModel();
    $this->_data['user'] = $usuario->getById($usuarioAuthModel->getId());
    $this->_data['user']['avatar_img'] = Picuri::picUri($this->_data['user']['avatar'], 50, 50, true);
  }

  protected function setRegistroSalvoSession ()
  {
    $session = new Session('adm_session');
    $session->set('registro_salvo', 'Registro salvo com sucesso!');
  }

  protected function setRegistroSalvoData ()
  {
    $session = new Session('adm_session');
    if ( $session->is_set('registro_salvo') ) {
      $this->_data['registro_salvo'] = $session->get('registro_salvo');
    }
    $session->un_set('registro_salvo');
  }

  //_# OPERAÇÕES COMUNS
  public function post_excluir ()
  {
    $itens = Post::aray('itens');

    foreach ( $itens as $item ) {
      list($tbl, $id) = explode('_', $item);
      $model_name = "\\src\\app\\adm\\models\\{$tbl}Model";
      $model = new $model_name;
      $model->excluir($id);
    }

    Header::redirect(Header::getReferer());
  }

  public function post_ativo ()
  {
    $this->_model->toggleAtivo(Post::text('id'), Post::checkbox('ativo'));
  }

}
