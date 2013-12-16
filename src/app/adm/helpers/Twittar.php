<?

namespace src\app\adm005\objects;

trait Twittar
{

  public function post_twittar ()
  {
    try {

      $twit = $_POST['twit'];

      // pega os acessos
      $configuracao = new \src\app\adm005\models\ConfiguracaoModel();
      $acessos = $configuracao->getById($this->user_table->id_entidade);
      //

      $Twitter = new \lib\SocialNetworks\Twitter\Twitter(array(
          'consumer_key' => $acessos->t_consumer_key,
          'consumer_secret' => $acessos->t_consumer_secret,
          'user_token' => $acessos->t_oauth_token,
          'user_secret' => $acessos->t_oauth_secret,
      ));

      if ( !$Twitter->post($twit) )
        throw new \Exception('Falha ao enviar twit, favor digitar um texto diferente');

      $secao = $this->_model->_table->getName();
      $nome_legivel = $this->_model->getMe();

      $this->log(null, array(
          'descricao' => $twit,
          'secao' => $secao,
          'nome_legivel' => $nome_legivel,
              ), 'T');


      $_SESSION['twit_salvo'] = true;

      $this->alljax_redirect($this->uri->lista);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

